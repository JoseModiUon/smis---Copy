<?php

namespace app\modules\feesManagement\controllers;


use app\models\SmStudentSponsor;
use app\models\search\SmStudentSponsorSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * SmStudentSponsorController implements the CRUD actions for SmStudentSponsor model.
 */
class SmStudentSponsorController extends Controller
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
     * Lists all SmStudentSponsor models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmStudentSponsorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmStudentSponsor model.
     * @param int $sponsor_id Sponsor ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sponsor_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($sponsor_id),
        ]);
    }

    /**
     * Creates a new SmStudentSponsor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmStudentSponsor();
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
                        'message' => 'Student Sponsor Created!',
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
     * Updates an existing SmStudentSponsor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sponsor_id Sponsor ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sponsor_id)
    {
        $model = $this->findModel($sponsor_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Student Sponsor updated successfully",
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
     * Deletes an existing SmStudentSponsor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sponsor_id Sponsor ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sponsor_id)
    {
        $this->findModel($sponsor_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmStudentSponsor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sponsor_id Sponsor ID
     * @return SmStudentSponsor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sponsor_id)
    {
        if (($model = SmStudentSponsor::findOne(['sponsor_id' => $sponsor_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
