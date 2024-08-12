<?php

namespace app\modules\courseRegistration\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\OrgCoursePrerequisite;
use app\models\search\OrgCoursePrerequisiteSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use yii\web\ServerErrorHttpException;

/**
 * OrgCoursePrerequisiteController implements the CRUD actions for OrgCoursePrerequisite model.
 */
class OrgCoursePrerequisiteController extends Controller
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
     * Lists all OrgCoursePrerequisite models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgCoursePrerequisiteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgCoursePrerequisite model.
     * @param int $course_prerequisite_id Course Prerequisite ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($course_prerequisite_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($course_prerequisite_id),
        ]);
    }

    /**
     * Creates a new OrgCoursePrerequisite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgCoursePrerequisite();
        // $transaction = Yii::$app->db->beginTransaction();
        // $spTransaction = Yii::$app->db2->beginTransaction();

        // try {
            if ($this->request->isPost) {            
                // dd($model->save());
                if ($model->load($this->request->post()) && $model->save()) {
                    // $autoSync = new AutoSynchronize([$model]);
                    // $autoSync->save();

                    // $transaction->commit();
                    // $spTransaction->commit();

                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => 'Course Prerequisite Created!',
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
        // } catch (\Throwable $th) {
            // $transaction->rollBack();
            // $spTransaction->rollBack();
            // $message = $th->getMessage();
            // if (YII_ENV_DEV) {
                // $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            // }
            // throw new ServerErrorHttpException($message, 500);
        // }

    }

    /**
     * Updates an existing OrgCoursePrerequisite model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $course_prerequisite_id Course Prerequisite ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($course_prerequisite_id)
    {
        $model = $this->findModel($course_prerequisite_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Course Prerequisite updated successfully",
                    'closeButton' => null,
                ]);
                // $autoSync = new AutoSynchronize();
                // if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                // }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgCoursePrerequisite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $course_prerequisite_id Course Prerequisite ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($course_prerequisite_id)
    {
        $this->findModel($course_prerequisite_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgCoursePrerequisite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $course_prerequisite_id Course Prerequisite ID
     * @return OrgCoursePrerequisite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($course_prerequisite_id)
    {
        if (($model = OrgCoursePrerequisite::findOne(['course_prerequisite_id' => $course_prerequisite_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
