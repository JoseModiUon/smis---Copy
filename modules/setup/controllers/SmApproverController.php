<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\SmApprover;
use app\models\search\SmApproverSearch;
use app\modules\setup\utils\DuplicateChecker;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmApproverController implements the CRUD actions for SmApprover model.
 */
class SmApproverController extends Controller
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
     * Lists all SmApprover models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmApproverSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmApprover model.
     * @param int $approver_id Approver ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($approver_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($approver_id),
        ]);
    }

    /**
     * Creates a new SmApprover model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmApprover();

        if ($this->request->isPost) {
            (new DuplicateChecker())->insert(SmApprover::class, $this, [
                ['ilike', 'approver', $this->request->post('SmApprover')['approver'] . '%', false],
                ['level' => $this->request->post('SmApprover')['level']]

            ]);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SmApprover model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $approver_id Approver ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($approver_id)
    {
        $model = $this->findModel($approver_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Approver Updated!');
            //return $this->redirect(['view', 'approver_id' => $model->approver_id]);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SmApprover model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $approver_id Approver ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($approver_id)
    {
        $this->findModel($approver_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmApprover model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $approver_id Approver ID
     * @return SmApprover the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($approver_id)
    {
        if (($model = SmApprover::findOne(['approver_id' => $approver_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
