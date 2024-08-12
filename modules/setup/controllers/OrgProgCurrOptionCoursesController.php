<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\OrgProgCurrOptionCourses;
use app\models\search\OrgProgCurrOptionCoursesSearch;

/**
 * OrgProgCurrOptionCoursesController implements the CRUD actions for OrgProgCurrOptionCourses model.
 */
class OrgProgCurrOptionCoursesController extends Controller
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
     * Lists all OrgProgCurrOptionCourses models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrOptionCoursesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCurrOptionCourses model.
     * @param int $option_course_id Option Course ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($option_course_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($option_course_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrOptionCourses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrOptionCourses();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Option Course Created!');
                return $this->redirect('index');
            }
            var_dump($model->getErrors());
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgCurrOptionCourses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $option_course_id Option Course ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($option_course_id)
    {
        $model = $this->findModel($option_course_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Option Course Updated!');
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgCurrOptionCourses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $option_course_id Option Course ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($option_course_id)
    {
        $this->findModel($option_course_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrOptionCourses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $option_course_id Option Course ID
     * @return OrgProgCurrOptionCourses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($option_course_id)
    {
        if (($model = OrgProgCurrOptionCourses::findOne(['option_course_id' => $option_course_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
