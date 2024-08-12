<?php


namespace app\modules\setup\controllers;
use Yii;
use app\models\SmWithdrawalType;
use app\models\search\SmWithdrawalTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmWithdrawalTypeController implements the CRUD actions for SmWithdrawalType model.
 */
class SmWithdrawalTypeController extends Controller
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
     * Lists all SmWithdrawalType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmWithdrawalTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmWithdrawalType model.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($withdrawal_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($withdrawal_type_id),
        ]);
    }

    /**
     * Creates a new SmWithdrawalType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmWithdrawalType();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
				 Yii::$app->getSession()->setFlash('success', 'Withdrawal Type Created!');
				 return $this->redirect(['index']);
               // return $this->redirect(['view', 'withdrawal_type_id' => $model->withdrawal_type_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SmWithdrawalType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($withdrawal_type_id)
    {
        $model = $this->findModel($withdrawal_type_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', 'Withdrawal Type Updated!');
           // return $this->redirect(['view', 'withdrawal_type_id' => $model->withdrawal_type_id]);
		    return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SmWithdrawalType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($withdrawal_type_id)
    {
        $this->findModel($withdrawal_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmWithdrawalType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return SmWithdrawalType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($withdrawal_type_id)
    {
        if (($model = SmWithdrawalType::findOne(['withdrawal_type_id' => $withdrawal_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
