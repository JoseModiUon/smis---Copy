<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\OrgAcademicSession;
use yii\web\NotFoundHttpException;
use app\models\search\OrgAcademicSessionSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use yii\web\ServerErrorHttpException;

/**
 * OrgAcademicSessionController implements the CRUD actions for OrgAcademicSession model.
 */
class OrgAcademicSessionController extends Controller
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
     * Lists all OrgAcademicSession models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgAcademicSessionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgAcademicSession model.
     * @param int $acad_session_id Acad Session ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($acad_session_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($acad_session_id),
        ]);
    }

    /**
     * Creates a new OrgAcademicSession model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgAcademicSession();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    $autoSync = new AutoSynchronize([$model]);
                    $autoSync->save();

                    $transaction->commit();
                    $spTransaction->commit();

                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => 'Academic Session Created!',
                        'closeButton' => null,
                    ]);
                    return $this->redirect('index');
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        } catch (\Throwable $th) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Updates an existing OrgAcademicSession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $acad_session_id Acad Session ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($acad_session_id)
    {
        $model = $this->findModel($acad_session_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Academic Session updated successfully",
                    'closeButton' => null,
                ]);
                $autoSync = new AutoSynchronize();
                if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgAcademicSession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $acad_session_id Acad Session ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($acad_session_id)
    {
        $this->findModel($acad_session_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgAcademicSession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $acad_session_id Acad Session ID
     * @return OrgAcademicSession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($acad_session_id)
    {
        if (($model = OrgAcademicSession::findOne(['acad_session_id' => $acad_session_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
