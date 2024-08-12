<?php

namespace app\modules\courseRegistration\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\CrProgCurrTimetable;
use app\models\search\CrProgCurrTimetableSearch;
use app\models\search\CrProgCurrTimetableCreateSearchRefac;
use app\modules\courseRegistration\utilities\TimetablePublisher;

use app\modules\courseRegistration\traits\CrProgCurrTimetableTrait;
use app\modules\courseRegistration\models\CrProgrammeCurrLectureTimetable as lecTimetable;
use kartik\growl\Growl;

/**
 * CrProgCurrTimetableController implements the CRUD actions for CrProgCurrTimetable model.
 */
class CrProgCurrTimetableController extends Controller
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
     * Lists all CrProgCurrTimetable models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect('cr-prog-curr-timetable/create');
        $searchModel = new CrProgCurrTimetableSearch();
        if (null !== Yii::$app->request->get('CrProgCurrTimetableSearch')) {
            $dataProvider = $searchModel->search($this->request->queryParams);
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

    /**
     * Displays a single CrProgCurrTimetable model.
     * @param int $timetable_id Timetable ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($timetable_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($timetable_id),
        ]);
    }

    /**
     * Creates a new CrProgCurrTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $searchModel = new CrProgCurrTimetableCreateSearchRefac();
        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams['CrProgCurrTimetableCreateSearchRefac']);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNew()
    {
        if ($this->request->isPost) {
            $params = Yii::$app->request->get();
            $return = $this->processPost();
            if ($return && !is_array($return)) {
                if ((new TimetablePublisher())->updatePublishStatus($this->request->post('CrProgCurrTimetable')['timetable_id'])) {
                    Yii::$app->getSession()->setFlash('new', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => 'Timetable Created Successfully!',
                        'closeButton' => null,
                    ]);
                    return $this->redirect(['cr-programme-curr-lecture-timetable/view', 'marksheetId' => $this->request->get('marksheetId')]);
                }
            } else {
                ['timetableModel' => $timetableModel, 'lecModel' => $lecModel] = $return;

                var_dump(array_merge($timetableModel->getErrors(), $lecModel->getErrors()));
            }
        } else {
            $model = $this->createTimetable(Yii::$app->request->get());
        }


        return $this->render('new', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing CrProgCurrTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $timetable_id Timetable ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($timetable_id)
    {
        $model = $this->findModel($timetable_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', "Timetable created");
            $session = Yii::$app->session;
            return $this->redirect(['index', 'CrProgCurrTimetableSearch[prog_curriculum_id]' => $session->get('prog_curriculum_id'), 'CrProgCurrTimetableSearch[acad_session_id]' => $session->get('acad_session_id')]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CrProgCurrTimetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $timetable_id Timetable ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($timetable_id)
    {
        $this->findModel($timetable_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CrProgCurrTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $timetable_id Timetable ID
     * @return CrProgCurrTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($timetable_id)
    {
        if (($model = CrProgCurrTimetable::findOne(['timetable_id' => $timetable_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    

    public function actionPublish()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $timetableIds = $this->request->post("timetableIds");
            $timetbl = new TimetablePublisher($timetableIds);
            if ($timetbl->publish($timetableIds)) {
                $transaction->commit();
                return $this->asJson(['success' => true, 'message' => 'timetable published successfully!']);
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return $this->asJson(['success' => false,  'message' => 'could not publish timetable!']);
        }
    }

}
