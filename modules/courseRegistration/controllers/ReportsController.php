<?php

namespace app\modules\courseRegistration\controllers;

use Yii;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ExStudentCourses;
use yii\data\ActiveDataProvider;
use app\models\CrProgCurrTimetable;
use app\models\StudentProgrammeCurriculum;
use app\models\ExMarksheet as ModelsExMarksheet;
use app\modules\courseRegistration\models\ExMarkSheet;
use app\modules\courseRegistration\traits\ExMarkTrait;
use app\modules\courseRegistration\models\CrCourseRegistration;
use app\modules\courseRegistration\models\search\ClassListSearch;
use app\models\CrCourseRegistration as ModelsCrCourseRegistration;
use app\models\CrCourseRegType;
use app\modules\courseRegistration\models\search\BlankMarkSheetSearch;
use app\modules\courseRegistration\traits\CrProgCurrTimetableTrait;
use app\modules\courseRegistration\models\search\ExamListViewSearch;
use app\modules\courseRegistration\models\search\ClassListViewSearch;
use app\modules\courseRegistration\models\search\MarksListViewSearch;
use app\modules\courseRegistration\models\search\MarksListStudentViewSearch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ReportsController extends Controller
{
    use CrProgCurrTimetableTrait;
    use ExMarkTrait;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }
    private function getResult($reg_no, $marksheet_id)
    {
        $data = explode('_', $marksheet_id);
        $year = $data[0];
        $course_id = array_reverse($data)[1] . '_' . array_reverse($data)[0];
        $id = $reg_no . '-' . $year . '-' . $course_id;
        return ExStudentCourses::find()->where(['course_registration_id' => $id])->one()->result_status;
    }

    public function actionGetExcelFile()
    {
        $mySpreadsheet = new Spreadsheet();

        // delete the default active sheet
        $mySpreadsheet->removeSheetByIndex(0);

        // Create "Sheet 1" tab as the first worksheet.
        // https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/adding-a-new-worksheet
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "Sheet 1");
        $mySpreadsheet->addSheet($worksheet, 0);


        // sheet 1 contains the birthdays of famous people.
        $sheetData = [
            ["Student Details", "Exam Type", "Course Work Mark / CAT", "Exam Mark", 'Result Status'],
        ];

        foreach ($this->request->post()['data'] as $row) {
            $details = $row['student_number'] . ' -' . $row['surname'] . ' ' . $row['other_names'];
            $status = $this->getResult($row['student_number'], $row['mrksheet_id']);
            $sheetData[] = [$details, $row['course_reg_type_name'], $row['course_work_mark'], $row['exam_mark'], $status];
        }

        $worksheet->fromArray($sheetData);
        $worksheets = [$worksheet];

        foreach ($worksheets as $worksheet) {
            foreach ($worksheet->getColumnIterator() as $column) {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        try {
            // Save to file.
            $writer = new Xlsx($mySpreadsheet);
            ob_start();
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            $out = base64_encode($excelOutput);

            return $this->asJson(['success' => true, 'data' => $out]);
        } catch (\Exception $e) {
            return $this->asJson([
                'success' => false, 'message' => 'could not generate file.'
            ]);
        }
    }
    public function actionGetCourseExcelFile()
    {

        $data = json_decode(base64_decode($this->request->post()['data']), true);

        $getResult = function ($marksheet_id, $reg_no) {
            $data = explode('_', $marksheet_id);
            $year = $data[0];
            $course_id = array_reverse($data)[1] . '_' . array_reverse($data)[0];
            $id = $reg_no . '-' . $year . '-' . $course_id;
            return ExStudentCourses::find()->where(['course_registration_id' => $id])->one();
        };

        $getExamType = function ($marksheet_id, $reg_no) use ($getResult) {

            $res = $getResult($marksheet_id, $reg_no);
            return CrCourseRegType::find()->where(['course_reg_type_code' => $res->examtype_code])->one()->course_reg_type_name;
        };

        $mySpreadsheet = new Spreadsheet();

        // delete the default active sheet
        $mySpreadsheet->removeSheetByIndex(0);

        // Create "Sheet 1" tab as the first worksheet.
        // https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/adding-a-new-worksheet
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "Sheet 1");
        $mySpreadsheet->addSheet($worksheet, 0);


        // sheet 1 contains the birthdays of famous people.
        $sheetData = [
            ["Course Details", 'Exam Type', "Course Work Mark / CAT", "Exam Mark", 'Result Status'],
        ];


        foreach ($data as $row) {
            $details = $row['course_code'] . ' - ' . $row['course_name'];
            $sheetData[] = [
                $details,
                $getExamType($row['mrksheet_id'], $row['registration_number']),
                $row['course_work_mark'],
                $row['exam_mark'],
                $getResult($row['mrksheet_id'], $row['registration_number'])->result_status
            ];
        }

        $worksheet->fromArray($sheetData);
        $worksheets = [$worksheet];

        foreach ($worksheets as $worksheet) {
            foreach ($worksheet->getColumnIterator() as $column) {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        try {
            // Save to file.
            $writer = new Xlsx($mySpreadsheet);
            ob_start();
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            $out = base64_encode($excelOutput);

            return $this->asJson(['success' => true, 'data' => $out]);
        } catch (\Exception $e) {
            return $this->asJson([
                'success' => false, 'message' => 'could not generate file.'
            ]);
        }
    }
    private function getModel()
    {
        return new class() extends yii\base\Model
        {
            /**
             * @var UploadedFile
             */
            public $file;

            public function rules()
            {
                return [
                    [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx'],
                ];
            }

            public function formName()
            {
                return 'UploadForm';
            }

            public function upload()
            {
                if ($this->validate()) {

                    $folder = Yii::getAlias('@app') . '/temp/xlsx';
                    if (!is_dir($folder)) {
                        FileHelper::createDirectory($folder, $mode = 0775, $recursive = true);
                    }
                    $this->file->saveAs($folder . '/' . $this->file->baseName . '.' . $this->file->extension);
                    return true;
                } else {
                    return false;
                }
            }
        };
    }
    public function actionParseExcel()
    {
        $model = $this->getModel();

        try {

            $model->file = UploadedFile::getInstanceByName('file');

            $model->upload();
            $dest = Yii::getAlias('@app') . '/temp/xlsx/' . $model->file->baseName . '.' . $model->file->extension;

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($dest);
            $worksheet = $spreadsheet->getActiveSheet();
            $allData = json_decode(base64_decode($this->request->post()['allData']), true);
            $finalData = [];


            foreach ($worksheet->toArray() as $index => $row) {
                if ($index == 0) {
                    continue;
                }
                $reg_no = trim(explode('-', $row[0])[0]);
                $items = $this->findExamDetails($reg_no, $allData);
                $finalData[$reg_no] =  [...$items, 'course_work_mark' => $row[2], 'exam_mark' => $row[3]];
            }

            unlink($dest);
            return $this->asJson(['success' => true, 'data' => $finalData]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($model->getErrors());
        }
    }
    public function actionParseCourseExcel()
    {
        $model = $this->getModel();

        try {
            $model->file = UploadedFile::getInstanceByName('file');
            $model->upload();
            $dest = Yii::getAlias('@app') . '/temp/xlsx/' . $model->file->baseName . '.' . $model->file->extension;

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($dest);
            $worksheet = $spreadsheet->getActiveSheet();
            $allData = json_decode(base64_decode($this->request->post()['allData']), true);

            $finalData = [];

            $reg_no = str_replace('_', '/', $this->request->post()['reg_no']);
            foreach ($worksheet->toArray() as $index => $row) {
                if ($index == 0) {
                    continue;
                }
                $course_code = trim(explode('-', $row[0])[0]);
                $items = $this->findCourseExamDetails($course_code, $allData);
                $finalData[][$reg_no] = [...$items, 'course_work_mark' => $row[2], 'exam_mark' => $row[3]];
            }

            unlink($dest);
            return $this->asJson(['success' => true, 'data' => $finalData]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($model->getErrors());
        }
    }


    private function findCourseExamDetails($course_code, $data)
    {
        $items = [];
        foreach ($data as $key => $row) {
            if (in_array($course_code, $row)) {
                $items = ['timetable_id' => $row['timetable_id'], 'marksheet_id' => $row['marksheet_id']];
            }
        }
        return $items;
    }


    private function findExamDetails($reg_no, $data)
    {
        $items = [];
        foreach ($data as $key => $row) {
            if (in_array($reg_no, $row)) {
                $items = ['timetable_id' => $row['timetable_id'], 'marksheet_id' => $row['marksheet_id']];
            }
        }
        return $items;
    }

    public function actionClassList()
    {

        $searchModel = new ClassListSearch();

        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams['ClassListSearch']);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionExamList()
    {

        $searchModel = new ClassListSearch();

        if (!empty(Yii::$app->request->get())) {
            // dd($this->request->queryParams);
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('exam-list-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewClassList()
    {

        $searchModel = new ClassListViewSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('class-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewExamList()
    {
        $searchModel = new ExamListViewSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        return $this->render('exam-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewBlankMarksheet()
    {
        $searchModel = new ExamListViewSearch();

        $dataProvider = $searchModel->search($this->request->queryParams);


        return $this->render('blank-marksheet-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionMarksEntry()
    {
        $searchModel = new ClassListSearch();

        if (!empty(Yii::$app->request->get())) {
            // dd($this->request->queryParams);
            $dataProvider = $searchModel->search($this->request->queryParams['ClassListSearch']);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('marks-entry-course-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBlankMarksheet()
    {

        $searchModel = new BlankMarkSheetSearch();

        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams['ClassListSearch']);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('blank-exam-list-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionViewMarksEntryList()
    {

        $searchModel = new MarksListViewSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('marks-entry-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionViewStudentMarksEntryList()
    {

        $searchModel = new MarksListStudentViewSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('marks-entry-student-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * assign values to model dynamically
     * @param Object $model
     * @param array $params
     * @return void
     */
    private function assign(Object $model, array $params): void
    {
        foreach ($params as $key => $value) {
            if (in_array($key, $model->attributes())) {
                $model->{$key} = $value;
            }
        }
    }

    public function actionMarkGrade()
    {
        $columns = $this->generateExStudentColumnsData();
        foreach ($columns as $mark) {
            $marksheet = ModelsExMarksheet::findOne($mark['marksheet_id']);
            $exStudentCourses = ExStudentCourses::find()->where(['course_registration_id' => $mark['course_registration_id']])->one();
            if (!$exStudentCourses) {
                $exStudentCourses = new ExStudentCourses();
            }

            $this->assign($marksheet, $mark);
            $this->assign($exStudentCourses, $mark);

            // dd($exStudentCourses->save());
            $ok = $marksheet->save() && $exStudentCourses->save();
            if (!$ok) {
                break;
            }
        }
        if (!$ok) {
            return $this->asJson(['success' => false, 'message' => 'updating marks failed.']);
        }
        return $this->asJson(['success' => true, 'message' => 'marks updated successfully!']);
    }


    public function actionFindGrade()
    {
        [
            'reg_no' => $reg_no,
            'final_mark' => $final_mark
        ] = $this->request->post();

        $grading = StudentProgrammeCurriculum::find()
            ->select('grade')
            ->innerJoinWith(['student' => function (ActiveQuery $stu) use ($reg_no) {
                $stu->where(['registration_number' => str_replace("_", "/", $reg_no)]);
            }])
            ->innerJoinWith(['progCurriculum' => function (ActiveQuery $pr) {
                $pr->innerJoinWith(['gradingSystem' => function (ActiveQuery $prog) {
                    $prog->innerJoinWith(['gradingDetail']);
                }]);
            }])
            ->innerJoinWith(['smAcademicProgresses' => function (ActiveQuery $acad) {
                $acad->innerJoinwith(['academicLevel']);
            }])
            ->where(['>=', 'upper_bound', $final_mark])
            ->andWhere(['<=', 'lower_bound',  $final_mark])
            ->asArray()
            ->one();

        if ($grading) {
            return $this->asJson(['success' => true, 'grade' => $grading['grade']]);
        }
        return $this->asJson(['success' => false, 'grade' => '']);
    }


    private function sync()
    {
        $data = ExMarkSheet::find()->asArray()->all();


        foreach ($data as $row) {
            $course = new \app\models\ExMarksheet();
            $this->assign($course, $row);
            $ok = $course->save();
        }
    }
}
