<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\OrgSemesterCode;
use yii\web\NotFoundHttpException;
use app\models\search\OrgSemesterCodeSearch;
use app\modules\setup\utils\AutoSynchronize;
use app\modules\setup\utils\DuplicateChecker;
use kartik\growl\Growl;
use yii\web\ServerErrorHttpException;

/**
 * OrgSemesterCodeController implements the CRUD actions for OrgSemesterCode model.
 */
class OrgSemesterCodeController extends Controller
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
     * Lists all OrgSemesterCode models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgSemesterCodeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgSemesterCode model.
     * @param int $semester_code Semester Code
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($semester_code)
    {
        return $this->render('view', [
            'model' => $this->findModel($semester_code),
        ]);
    }

    /**
     * Creates a new OrgSemesterCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgSemesterCode();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
                $dpCheck = new DuplicateChecker();
                $dpCheck->insert(OrgSemesterCode::class, $this, [
                    ['ilike', 'semster_name', $this->request->post('OrgSemesterCode')['semster_name'] . '%', false]

                ]);
                if ($models = $dpCheck->getObjectModel()) {
                    $autoSync = new AutoSynchronize([$models]);
                    $autoSync->save();
                }
                $transaction->commit();
                $spTransaction->commit();

            
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
     * Updates an existing OrgSemesterCode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $semester_code Semester Code
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($semester_code)
    {
        $model = $this->findModel($semester_code);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Semester code updated succesfully!",
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
     * Deletes an existing OrgSemesterCode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $semester_code Semester Code
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($semester_code)
    {
        $this->findModel($semester_code)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgSemesterCode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $semester_code Semester Code
     * @return OrgSemesterCode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($semester_code)
    {
        if (($model = OrgSemesterCode::findOne(['semester_code' => $semester_code])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
