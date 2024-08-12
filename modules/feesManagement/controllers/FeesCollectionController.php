<?php

namespace app\modules\feesManagement\controllers;
use app\modules\feesManagement\models\ProgrammeCurriculum;
use app\modules\feesManagement\models\search\FeesCollectionSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ProgrammeCurriculumController implements the CRUD actions for ProgrammeCurriculum model.
 */
class FeesCollectionController extends Controller
{
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
     * Lists all ProgrammeCurriculum models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FeesCollectionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $queryParams = $this->request->queryParams;


        if (Yii::$app->request->isAjax && $searchModel->load(Yii::$app->request->get())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($searchModel);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'queryParams' => $queryParams,
        ]);
    }

    /**
     * Displays a single ProgrammeCurriculum model.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_id),
        ]);
    }

    /**
     * Creates a new ProgrammeCurriculum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgrammeCurriculum();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'prog_curriculum_id' => $model->prog_curriculum_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProgrammeCurriculum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_id)
    {
        $model = $this->findModel($prog_curriculum_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

        /**
     * Updates an existing ProgrammeCurriculum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Deletes an existing ProgrammeCurriculum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_id)
    {
        $this->findModel($prog_curriculum_id)->delete();

        return $this->redirect(['index']);
    }


    public function actionBulknon()
    {
        $request = Yii::$app->request;


        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys

        // dd($pks);


        // dd(Yii::$app->db->createCommand('SELECT * FROM smis.fss_sponsor_beneficiary WHERE sponsor_beneficiary_id IN (' . $request->post('pks') . ')')
        //     ->queryAll());

        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            // $model->delete();
            $model->billing_type_id = 1;
            $model->save();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true, 'forceReload' => '#crud-datatable-pjax'
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }

    }

    public function actionBulkreg()
    {
        $request = Yii::$app->request;


        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys

        // dd($pks);


        // dd(Yii::$app->db->createCommand('SELECT * FROM smis.fss_sponsor_beneficiary WHERE sponsor_beneficiary_id IN (' . $request->post('pks') . ')')
        //     ->queryAll());

        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            // $model->delete();
            $model->billing_type_id = 2;
            $model->save();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true, 'forceReload' => '#crud-datatable-pjax'
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the ProgrammeCurriculum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return ProgrammeCurriculum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_id)
    {
        if (($model = ProgrammeCurriculum::findOne(['prog_curriculum_id' => $prog_curriculum_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
