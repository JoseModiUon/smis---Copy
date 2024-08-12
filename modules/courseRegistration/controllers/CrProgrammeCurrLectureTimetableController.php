<?php

namespace app\modules\courseRegistration\controllers;

use app\models\CrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use app\models\search\CrProgrammeCurrLectureTimetableSearch;
use app\modules\courseRegistration\traits\CrProgCurrTimetableTrait;
use app\modules\courseRegistration\models\CrProgrammeCurrLectureTimetable as ModelsCrProgrammeCurrLectureTimetable;
use app\modules\courseRegistration\utilities\TimetablePublisher;
use kartik\form\ActiveForm;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Crap4j;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\ActiveQuery;
use yii\data\ArrayDataProvider;


/**
 * CrProgrammeCurrLectureTimetableController implements the CRUD actions for CrProgrammeCurrLectureTimetable model.
 */
class CrProgrammeCurrLectureTimetableController extends Controller
{
    use CrProgCurrTimetableTrait;
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

    /**
     * Lists all CrProgrammeCurrLectureTimetable models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $queryParams = $this->request->queryParams['params']['CrProgCurrTimetableCreateSearchRefac'];

        $model =
            $this->getModel()
            ->where($this->buildConditions($queryParams))
            ->orderBy([
                'org_days.day_id' => SORT_ASC,
                'cr_programme_curr_lecture_timetable.start_time' => SORT_ASC
            ]);
        $data = $model->asArray()->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => false,
        ]);

        return $this->render('index', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
            'data' => $data,
            'params' => $queryParams,
        ]);
    }

    private function buildConditions($queryParams)
    {
        $conditions = [];

        if (!empty(array_values($queryParams))) {
            if ($queryParams['prog_curriculum_id']) {
                $conditions['org_programme_curriculum.prog_curriculum_id'] = $queryParams['prog_curriculum_id'];
            }
            if ($queryParams['acad_session_id']) {
                $conditions['org_academic_session.acad_session_id'] = $queryParams['acad_session_id'];
            }
            if ($queryParams['study_group_id']) {
                $conditions['org_study_group.study_group_id'] = $queryParams['study_group_id'];
            }
            if ($queryParams['study_centre_id']) {
                $conditions['org_study_centre.study_centre_id'] = $queryParams['study_centre_id'];
            }
            if ($queryParams['semester_type_id']) {
                $conditions['org_semester_type.semester_type_id'] = $queryParams['semester_type_id'];
            }
            if ($queryParams['semester_code']) {
                $conditions['org_semester_code.semester_code'] = $queryParams['semester_code'];
            }
            if ($queryParams['programme_level_id']) {
                $conditions['org_prog_level.programme_level_id'] = $queryParams['programme_level_id'];
            }
        }

        return $conditions;
    }


    /**
     * Prepares querying model
     *
     * @return ActiveQuery
     */
    private function getModel(): ActiveQuery
    {
        return CrProgrammeCurrLectureTimetable::find()
            ->select([
                'org_courses.course_code',
                'org_courses.course_name',
                'org_days.day_name',
                'cr_class_groups.class_description',
                'cr_programme_curr_lecture_timetable.start_time',
                'cr_programme_curr_lecture_timetable.end_time',
                'cr_programme_curr_lecture_timetable.timetable_id',
                'org_rooms.room_name',

            ])
            ->joinWith(['timetable' => function (ActiveQuery $q) {
                $q->joinWith(['orgProgCurrCourse' => function (ActiveQuery $r) {
                    $r->joinWith(['course', 'semesterCode']);
                }]);
                $q->joinWith(['progCurriculumSemGroup' => function ($t) {
                    $t->joinWith(['progCurriculumSemester' => function (ActiveQuery $r) {
                        $r->joinWith(['progCurriculum', 'orgSemesterType']);
                        $r->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
                            $a->joinWith('acadSession');
                        }]);
                    }]);
                    $t->joinWith(['studyCentreGroup' => function (ActiveQuery $k) {
                        $k->joinWith(['studyCentre', 'studyGroup']);
                    }]);
                    $t->joinWith(['programmeLevel']);
                }]);
            }])

            ->joinWith(['day', 'room', 'classGroups']);
    }

    /**
     * Displays a single CrProgrammeCurrLectureTimetable model.
     * @param int $lecture_timetable_id Lecture Timetable ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        $request = Yii::$app->request->get();
        $model = CrProgCurrTimetable::find()->where([
            "mrksheet_id" => $request['marksheetId'],
        ])->one();

        $searchModel = new CrProgrammeCurrLectureTimetableSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, ['timetable_id' => $model->timetable_id]);


        return $this->render('timetables', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => array_merge($request, ['timetable_id' => $model->timetable_id]),
            'tModel' => $model
        ]);
    }

    /**
     * Creates a new CrProgrammeCurrLectureTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CrProgrammeCurrLectureTimetable();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $session = Yii::$app->session;
                return $this->redirect(['view', 'timetable_id' => $session->get('timetable_id'), 'course_id' => $session->get('course_id')]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing CrProgrammeCurrLectureTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $lecture_timetable_id Lecture Timetable ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {

        $params = Yii::$app->request->get();
        $model = CrProgrammeCurrLectureTimetable::findOne($params['lecture_timetable_id']);

        if ($this->request->isPost) {

            $return = $this->processPost();
            if ($return && !is_array($return)) {
                if ((new TimetablePublisher())->updatePublishStatus($model['timetable_id'])) {
                    Yii::$app->getSession()->setFlash('success', "Timetable Updated");
                    return $this->redirect(['view', ...$params]);
                }
            } else {
                dd($return);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'params' => $params
        ]);
    }

    /**
     * Deletes an existing CrProgrammeCurrLectureTimetable model.
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $publisher = new TimetablePublisher();
        $post = Yii::$app->request->post();
        $model = CrProgrammeCurrLectureTimetable::findOne($post['lecture_timetable_id']);

        if (($publisher->updatePublishStatus($model['timetable_id']))) {
            $status = $model->delete() &&
                ModelsCrProgrammeCurrLectureTimetable::findOne($post['lecture_timetable_id'])->delete();
        }
        return $this->asJson([
            'success' => isset($status) && $status ?? false,
        ]);
    }
}
