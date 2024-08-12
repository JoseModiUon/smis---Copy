<?php

namespace app\modules\studentRecords\controllers;

use app\models\AdmittedStudent;
use app\models\OrgKuccpsProgMap;
use app\models\search\AdmittedStudentSearch;
use app\models\Student;
use app\modules\studentRecords\models\search\StudentSearchUtility;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UtilityController actions for Student Utilities.
 */
class UtilityController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ],
        );
    }

    /**
     * @return string
     */
    public function actionStudentSearch(): string
    {
        $searchModel = new StudentSearchUtility();
        $college = Yii::$app->request->getQueryParam('college','');
        $session = Yii::$app->request->getQueryParam('session','');
        $programme = Yii::$app->request->getQueryParam('programme','');
        $extraFilter = ['college'=>$college,'session'=>$session,'programme'=>$programme];
        $dataProvider = $searchModel->search($this->request->queryParams,$extraFilter);
        return $this->render('student-search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'programme' => $programme,
            'session' => $session,
        ]);
    }

    /**
     * Student Details view after search is found
     * @param $id
     * @return string
     */
    public function actionStudentDetails($id): string
    {
        $model = Student::findOne($id);
        return $this->render('search/_personal', [
            'model' => $model,
            'student_id' => $id,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionUploadAdmittedList(): Response|string
    {
        $model = new AdmittedStudent();

        if ($this->request->isPost) {
            $time = time();
            $user = Yii::$app->user->id;
            $model->admit_list = UploadedFile::getInstance($model, 'admit_list');

            if ($model->admit_list) {
                $name='f_'. $user . '_' . $time . '.' . $model->admit_list->extension;
                $model->admit_list->saveAs(Yii::$app->basePath.'/uploads/data/admitted_students/'. $name);
                $this->processXL($name);
            }else{
//                Yii::$app->session->setFlash('error', 'Error Uploading File');
                Yii::$app->getSession()->setFlash('uploadList', [
                    'type' => 'warning',
                    'message' => 'Error Uploading File',
                    'title' => 'Failed',
                ]);
            }

            Yii::$app->session->set('time_taken',time() - $time);
            return $this->redirect(['upload-admitted-list']);

        }

        return $this->render('upload-admitted-list',['model'=>$model]);
    }

    /**
     * @param $fileName
     * @return void
     */
    public function processXL($fileName): void
    {
        ini_set('max_execution_time', 60*10); // 10 mins
        ini_set('memory_limit', '2048M'); // 2GB
        try {
            $dir = Yii::$app->basePath.'/uploads/data/admitted_students/';
            $file = $dir . $fileName;
            $inputFileType = IOFactory::identify($file);
            $objReader = IOFactory::createReader($inputFileType);
            $objExcel = $objReader->load($file);

            $errSheet = new Spreadsheet();
            $eSheet = $errSheet->getActiveSheet();

            $sheet = $objExcel->getActiveSheet();
            $head = ['KCSE INDEX NO','KCSE YEAR','SURNAME','OTHER NAMES','PHONE NUMBER'
                ,'ALTERNATIVE PHONE NUMBER','EMAIL',
                'ALTERNATIVE EMAIL','POSTAL ADDRESS','POSTAL CODE','TOWN','KUCCPS PROGRAMME CODE'];
            $eSheet->fromArray([$head],NULL,'A1');
            $row = $eSheet->getHighestRow()+1;
            $colCount = 1 + (ord($sheet->getHighestColumn()) - ord('A'));

            if (trim($colCount < 12)) {
                throw new Exception("The uploaded file has issues. Kindly make sure the file is arranged 
                as per the Download Template");
            }

            $rowCount = $sheet->getHighestRow();
            $error_count=0;
            for ($i = 1; $i <= $rowCount; $i++) {
                if ($i == 1) {
                    // Skip Header
                    continue;
                }
                //Process remaining rows
                // DEPT_CODE, FAC_CODE, DEPT_NAME, WEBSITE, EMAIL, STATUS
                //Col 1 - KCSE INDEX
                //Col 2 - KCSE YEAR
                //Col 3 - SURNAME
                //Col 4 - GENDER
                //Col 5 - OTHER NAMES
                //Col 6 - PHONE NUMBER
                //Col 7 - ALTERNATIVE PHONE NUMBER
                //Col 8 - EMAIL
                //Col 9 - ALTERNATIVE EMAIL
                //Col 10 - POSTAL ADDRESS
                //Col 11 - POSTAL CODE
                //Col 12 - TOWN
                //Col 13 - KUCCPS PROGRAMME CODE

                $indexNo = trim($sheet->getCell("A$i"));
                $year = trim(($sheet->getCell("B$i")));
                $surname = trim(strtoupper($sheet->getCell("C$i")));
                $otherName = trim(strtolower($sheet->getCell("D$i")));
                $gender = trim(strtolower($sheet->getCell("E$i")));
                $phoneNo = trim(($sheet->getCell("F$i")));
                $phoneNoAlt = trim(($sheet->getCell("G$i")));
                $email = trim(strtolower($sheet->getCell("H$i")));
                $emailAlt = trim(strtolower($sheet->getCell("I$i")));
                $postalAddr = trim(strtoupper($sheet->getCell("J$i")));
                $postCode = trim(($sheet->getCell("K$i")));
                $town = trim(strtoupper($sheet->getCell("L$i")));
                $kCode = trim(($sheet->getCell("M$i")));

                $codeMap = OrgKuccpsProgMap::find()->where(['kuccps_prog_code'=>$kCode])->one();
                if($codeMap===null){
                    $error_count++;
                    $eSheet->fromArray([$indexNo,$year,$surname,$otherName,
                        $gender,$phoneNo,
                        $phoneNoAlt,$email,$emailAlt,$postalAddr,
                        $postCode,$town,$kCode,'Could not map KUCCUPS code'],NULL,"A$row");
                    $eSheet->getStyle("A$row:M$row")->getFill()->setFillType('solid')
                        ->getStartColor()->setRGB('FFA500');
                    $row++;
                    continue;
                }
                // check phone number issue
                try{
                    $cntPhone = Yii::$app->phoneNumber->formatKe($phoneNo,true);
                    $cntPhoneAlt = Yii::$app->phoneNumber->formatKe($phoneNoAlt,true);
                }catch (Exception $e){
//                    $error_count++;
//                    $sheet->setCellValue("N$i",$e->getMessage());
//                    $sheet->getStyle("A$i:M$i")->getFill()->setFillType('solid')
//                        ->getStartColor()->setRGB('FFA500');
//                    continue;
                    $cntPhone=$phoneNo;$cntPhoneAlt=$phoneNoAlt;
                }

                $model = AdmittedStudent::find()
                    ->where(['and',['kcse_index_no'=>$indexNo],['kcse_year'=>$year],])
                    ->one();
                if($model!==null){
                    $error_count++;
                    $eSheet->fromArray([$indexNo,$year,$surname,$otherName,
                        $gender,$phoneNo,
                        $phoneNoAlt,$email,$emailAlt,$postalAddr,
                        $postCode,$town,$kCode,'Already Exists'],NULL,"A$row");
                    $eSheet->getStyle("A$row:M$row")->getFill()->setFillType('solid')
                        ->getStartColor()->setRGB('FFA500');
                    $row++;
                }else {
                    $model = new AdmittedStudent();

                    // Set the DB attributes
                    $model->kcse_index_no = $indexNo;
                    $model->kcse_year = $year;
                    $model->surname = strtoupper($surname);
                    $model->other_names = strtoupper($otherName);
                    $model->gender = strtoupper($gender);
                    $model->primary_phone_no = $cntPhone;
                    $model->alternative_phone_no = $cntPhoneAlt;
                    $model->primary_email = $email;
                    $model->alternative_email = $emailAlt;
                    $model->post_address = $postalAddr;
                    $model->post_code = $postCode;
                    $model->town = $town;
                    $model->kuccps_prog_code = $kCode;
                    // Extras
//                    $model->source_id = 1; // KUCCUPS default
//                    print_r(Yii::$app->request->post());exit;
                    $model->intake_code = Yii::$app->request->post('intake_id');
                    $model->source_id = Yii::$app->request->post('source_id');
                    $model->admission_status='PRE-REGISTRATION';
                    $model->uon_prog_code = $codeMap->uon_prog_code;
                    $model->student_category_id = 1;

                    if (!$model->validate()) {
                        $msgE = implode('||', array_values($model->getFirstErrors()));
                        $error_count++;
                        $eSheet->fromArray([$indexNo,$year,$surname,$otherName,
                            $gender,$phoneNo,
                            $phoneNoAlt,$email,$emailAlt,$postalAddr,
                            $postCode,$town,$kCode,$msgE],NULL,"A$row");
                        $eSheet->getStyle("A$row:M$row")->getFill()->setFillType('solid')
                            ->getStartColor()->setRGB('FFA500');
                        $row++;
                        continue;
                    }
                    if (!$model->save()) {
                        $msgE=implode('|| ', array_values($model->getFirstErrors()));
                        $error_count++;
                        $eSheet->fromArray([$indexNo,$year,$surname,$otherName,
                            $gender,$phoneNo,
                            $phoneNoAlt,$email,$emailAlt,$postalAddr,
                            $postCode,$town,$kCode,$msgE],NULL,"A$row");
                        $eSheet->getStyle("A$row:M$row")->getFill()->setFillType('solid')
                        ->getStartColor()->setRGB('FFA500');
                        $row++;
                    }
                    else {
                        $model->password = md5($model->adm_refno);
                        $model->save();
                    }
                }
            }
            if(!$error_count){
                Yii::$app->getSession()->setFlash('uploadList', [
                    'type' => 'success',
                    'message' => "Admitted Students have been uploaded",
                    'title' => 'Success',
                ]);
            }else{
                if($inputFileType){
                    $dir = '/uploads/data/admitted_students/error/';
                    $eFile = "{$dir}error_{$fileName}";
                    $eUrl = Url::to([ "download" ,
                        'file'=>'eFile','fpath'=>Yii::$app->basePath.$eFile]);
                    $objWriter = IOFactory::createWriter($errSheet, $inputFileType);
                    $objWriter->save(Yii::$app->basePath."{$eFile}");

                    throw new Exception("An error occured while processing upload. Please review the file by downloading
                            <br/><br/><br/><a class='btn btn-default btn-xs' href='$eUrl' target='_blank'>Download File</a> ");
                }
            }
        }catch (Exception $e){
            Yii::$app->getSession()->setFlash('uploadList', [
                'type' => 'warning',
                'message' => "Upload not Successful,\n".$e->getMessage(),
                'title' => 'Failed Process',
            ]);
        }
    }


    /**
     * @param string $file
     * @param string $fpath
     * @return void|\yii\console\Response|Response
     */
    public function actionDownload(string $file='admitted_student_template', string $fpath='')
    {
        $path = Yii::getAlias('@app') . '/templates'.'/'.$file.'.xlsx';
        if(strtoupper($file)==='EFILE'){
            $path=$fpath;
        }

        if(file_exists($path))
        {
            return Yii::$app->response->sendFile($path);
        }else{
//            Yii::$app->session->setFlash('error', 'The requested resource does not exist.');
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'warning',
                'message' => 'The requested resource does not exist.',
                'title' => 'Failed',
            ]);
        }
    }
}
