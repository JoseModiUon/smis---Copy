<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgCourses;
use app\models\search\OrgCoursesSearch;
use app\modules\setup\utils\DuplicateChecker;
use app\modules\setup\utils\AutoSynchronize;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;

/**
 * OrgCoursesController implements the CRUD actions for OrgCourses model.
 */
class OrgCoursesController extends Controller
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
     * Lists all OrgCourses models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgCoursesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgCourses model.
     * @param int $course_id Course ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($course_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($course_id),
        ]);
    }

    /**
     * Creates a new OrgCourses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgCourses();

        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {


                //Checking if the records are a duplicate in the db
                $dpCheck = new DuplicateChecker();

                $dpCheck->insert(OrgCourses::class, $this, [
                    ['ilike', 'course_code',  $this->request->post('OrgCourses')['course_code']. '%', false],
                    ['ilike', 'course_name', $this->request->post('OrgCourses')['course_name']. '%', false],
                    ['org_unit_id' => $this->request->post('OrgCourses')['org_unit_id']],
                    ['category_id' => $this->request->post('OrgCourses')['category_id']],
    
                ]);


                if ($models = $dpCheck->getObjectModel()) {
                    $autoSync = new AutoSynchronize([$models]);
                    // dd($autoSync->save());

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
     * Updates an existing OrgCourses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $course_id Course ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($course_id)
    {
        $model = $this->findModel($course_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->course_name.' Updated!');
            
            $autoSync = new AutoSynchronize();
            dd($autoSync->bulkSync(get_class($model)));
                if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgCourses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $course_id Course ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($course_id)
    {
        $this->findModel($course_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgCourses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $course_id Course ID
     * @return OrgCourses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($course_id)
    {
        if (($model = OrgCourses::findOne(['course_id' => $course_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
