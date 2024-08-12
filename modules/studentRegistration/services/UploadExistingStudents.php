<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 1/16/2024
 * @time: 11:06 AM
 */

namespace app\modules\studentRegistration\services;

use app\helpers\SmisHelper;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\SPAdmittedStudent;
use app\modules\studentRegistration\models\Student;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

final class UploadExistingStudents extends UploadStudents
{
    private const ADMISSION_CLEARANCE_CLEARED_STATUS = 'CLEARED';
    private const REGISTERED_STATUS = 'REGISTERED';
    private array $excelStudent = [];

    /**
     * @throws Exception
     */
    public function save(array $file): array
    {
        /**
         * @Note These columns must match what data they represent in the uploaded Excel file.
         * If cells order in the template is changed, this mapping must be updated.
         */
        $academicYearCol = 'A';
        $globalSemesterCol = 'B';
        $progCodeCol = 'C';
        $regNumCol = 'D';
        $surnameCol = 'E';
        $otherNamesCol = 'F';
        $genderCol = 'G';
        $studyCenterIdCol = 'H';
        $studyGroupIdCol = 'I';
        $studentCatIdCol = 'J';
        $intakeCol = 'K';
        $sourceCol = 'L';
        $sponsorCol = 'M';
        $priEmailCol = 'N';
        $altEmailCol = 'O';
        $priPhoneCodeCol = 'P';
        $priPhoneCol = 'Q';
        $altPhoneCodeCol = 'R';
        $altPhoneCol = 'S';
        $postAddressCol = 'T';
        $postCodeCol = 'U';
        $postTownCol = 'V';
        $nationalIdCol = 'W';
        $birthCertCol = 'X';
        $passportCol = 'Y';
        $serviceCol = 'Z';
        $serviceNumCol = 'AA';
        $nationalityCol = 'AB';
        $dobCol = 'AC';
        $bloodGroupCol = 'AD';

        // Attempt to upload data file in the server and then read students from it
        $filename = $this->uploadFile($file);
        $filepath = Yii::getAlias('@uploadExistingStudentsPath') . $filename;
        $excelStudents = $this->readExcelFile($filepath);

        // Attempt to store students in db
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try {
            $duplicateStudents = [];

            if (!empty($excelStudents)) {
                foreach ($excelStudents as $excelStudent) {
                    $this->excelStudent = $excelStudent;

                    $regNumber = $this->excelFileColVal($regNumCol);

                    /**
                     * Academic year is the year the students joined the university but not exactly the academic year in which
                     * this the backlog data is being uploaded.
                     * @Note This is not a promotion routine. All students will be uploaded into year 1, semester 1
                     * and thereafter promoted from a different routine.
                     * @Note The global semester is just to keep track of which semester in the calendar year of the
                     * university did the students join.
                     */
                    $academicYear = $this->excelFileColVal($academicYearCol);
                    $globalSemester = $this->excelFileColVal($globalSemesterCol);
                    $semester = (new Query())
                        ->select(['ass.acad_session_semester_id'])
                        ->from('smis.org_academic_session_semester ass')
                        ->innerJoin('smis.org_academic_session year', 'year.acad_session_id=ass.acad_session_id')
                        ->where([
                            'ass.acad_session_semester_id' => $globalSemester,
                            'year.acad_session_name' => $academicYear
                        ])->one();

                    if (empty($semester)) {
                        throw new UnprocessableEntityHttpException(
                            'The academic year or semester in which the student ' . $regNumber . ' joined the
                            university must be provided.');
                    }

                    // Ensure we don't reupload as student
                    $student = Student::find()->select('student_number')->where(['student_number' => $regNumber])
                        ->asArray()->one();
                    if (!empty($student)) {
                        $duplicateStudents[] = $regNumber;
                        continue;
                    }

                    $admittedStudent = new AdmittedStudent();
                    $admittedStudent->source_id = $this->excelFileColVal($sourceCol);
                    $admittedStudent->intake_code = $this->excelFileColVal($intakeCol);
                    $admittedStudent->student_category_id = $this->excelFileColVal($studentCatIdCol);
                    $admittedStudent->uon_prog_code = $this->excelFileColVal($progCodeCol);

                    $studyCenterGroup = (new Query())
                        ->select(['scg.study_centre_group_id'])
                        ->from(SMIS_DB_SCHEMA . '.org_study_centre_group scg')
                        ->where([
                            'scg.study_centre_id' => $this->excelFileColVal($studyCenterIdCol),
                            'scg.study_group_id' => $this->excelFileColVal($studyGroupIdCol)
                        ])
                        ->one();

                    if (empty($studyCenterGroup)) {
                        throw new NotFoundHttpException('Study center group not found for student ' . $regNumber .
                            ' Please create one and continue.');
                    }
                    $admittedStudent->study_centre_group_id = $studyCenterGroup['study_centre_group_id'];

                    $admittedStudent->surname = $this->excelFileColVal($surnameCol);
                    $admittedStudent->other_names = $this->excelFileColVal($otherNamesCol);
                    $admittedStudent->primary_phone_country_code = $this->excelFileColVal($priPhoneCodeCol);
                    $admittedStudent->primary_phone_no = $this->excelFileColVal($priPhoneCol);
                    $admittedStudent->alternative_phone_country_code = $this->excelFileColVal($altPhoneCodeCol);
                    $admittedStudent->alternative_phone_no = $this->excelFileColVal($altPhoneCol);
                    $admittedStudent->primary_email = $this->excelFileColVal($priEmailCol);
                    $admittedStudent->alternative_email = $this->excelFileColVal($altEmailCol);
                    $admittedStudent->post_address = $this->excelFileColVal($postAddressCol);
                    $admittedStudent->post_code = $this->excelFileColVal($postCodeCol);
                    $admittedStudent->town = $this->excelFileColVal($postTownCol);
                    $admittedStudent->national_id = $this->excelFileColVal($nationalIdCol);
                    $admittedStudent->birth_cert_no = $this->excelFileColVal($birthCertCol);
                    $admittedStudent->passport_no = $this->excelFileColVal($passportCol);
                    $admittedStudent->gender = $this->excelFileColVal($genderCol);
                    $admittedStudent->service = $this->excelFileColVal($serviceCol);
                    $admittedStudent->service_number = $this->excelFileColVal($serviceNumCol);
                    $admittedStudent->nationality = $this->excelFileColVal($nationalityCol);
                    $dateOfBirth = \DateTimeImmutable::createFromFormat('d/m/Y', $this->excelFileColVal($dobCol));
//                    $admittedStudent->date_of_birth = $dateOfBirth->format('Y-m-d');
                    $admittedStudent->sponsor = $this->excelFileColVal($sponsorCol);
                    $admittedStudent->blood_group = $this->excelFileColVal($bloodGroupCol);
                    $admittedStudent->reg_no_serial_no = explode('/', $regNumber)[1];
                    $admittedStudent->password = Yii::$app->getSecurity()->generatePasswordHash($admittedStudent->reg_no_serial_no);
                    $admittedStudent->doc_submission_status = true;
                    $admittedStudent->document_sync_status = true;
                    $admittedStudent->clearance_status = self::ADMISSION_CLEARANCE_CLEARED_STATUS;
                    $admittedStudent->profile_sync_status = true;
                    $admittedStudent->admission_status = self::REGISTERED_STATUS;

                    if (!$admittedStudent->save()) {
                        if (!$admittedStudent->validate()) {
                            $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                            throw new UnprocessableEntityHttpException($errorMessage);
                        } else {
                            throw new UnprocessableEntityHttpException('Admitted Students data failed to save.');
                        }
                    }

                    // Sync the admitted student data with the student portal db
                    $spAdmittedStudent = new SPAdmittedStudent();
                    $spAdmittedStudent->setAttributes($admittedStudent->attributes);
                    $spAdmittedStudent->primary_phone_no = $admittedStudent->primary_phone_country_code . $admittedStudent->primary_phone_no;
                    $spAdmittedStudent->alternative_phone_no = $admittedStudent->alternative_phone_country_code . $admittedStudent->alternative_phone_no;
                    if (!$spAdmittedStudent->save()) {
                        if (!$spAdmittedStudent->validate()) {
                            $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                            throw new UnprocessableEntityHttpException($errorMessage);
                        } else {
                            throw new UnprocessableEntityHttpException('Admitted Students data failed to sync.');
                        }
                    }

                    new RegisterStudents($admittedStudent, $regNumber, $academicYear, (int)$globalSemester);

                    /*
                     * @todo Create and email student login credentials
                     */
                }
                $transaction->commit();
                $spTransaction->commit();
            }
            return $duplicateStudents;
        } catch (Exception $ex) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            throw $ex;
        }
    }

    /**
     * @param array $files
     * @return string
     * @throws Exception
     */
    private function uploadFile(array $files): string
    {
        $path = Yii::getAlias('@uploadExistingStudentsPath');

        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new Exception('Failed to create uploads directory.');
            }
        }

        $file = $files['template'];

        if ($file['error'] !== 0) {
            throw new Exception('An error occurred while trying to upload files.');
        }

        $validExtensions = ['xlsx', 'xls'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $validExtensions)) {
            throw new Exception('File extension not allowed.');
        }

        $newFileName = strtolower(pathinfo($file['name'], PATHINFO_FILENAME));
        $newFileName = preg_replace('/\s/', '_', $newFileName);
        $newFileName .= '_by_' . Yii::$app->user->identity->username . '_at_' . time() . '.' . $ext;

        $destinationFile = $path . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $destinationFile)) {
            throw new Exception('Failed to move uploaded file.');
        }

        return $newFileName;
    }

    /**
     * @param string $filePath
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function readExcelFile(string $filePath): array
    {
        //Read file
        $newSheetData = [];
        $inputFileName = $filePath;
        $inputFileType = IOFactory::identify($inputFileName);
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Some cells contain null values. Take these out
        foreach ($sheetData as $data) {
            $dataKeys = array_keys($data);
            foreach ($dataKeys as $key) {
                if (is_null($data[$key])) {
                    unset($data[$key]);
                }
            }
            $newSheetData[] = $data;
        }

        // Remove column titles
        array_shift($newSheetData);

        // Remove empty rows
        foreach ($newSheetData as $key => $value) {
            if (empty($value)) {
                unset($newSheetData[$key]);
            }
        }

        return $newSheetData;
    }

    /**
     * @param $col
     * @return mixed|string
     */
    private function excelFileColVal($col): mixed
    {
        return array_key_exists($col, $this->excelStudent) ? $this->excelStudent[$col] : '';
    }
}