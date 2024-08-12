<?php

namespace app\modules\setup\controllers;
use Yii;
use app\models\OrgUnitCourse;
use app\models\search\OrgUnitCourseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgUnitCourseController implements the CRUD actions for OrgUnitCourse model.
 */
class OrgUnitCourseController extends Controller
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
     * Lists all OrgUnitCourse models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgUnitCourseSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgUnitCourse model.
     * @param int $org_unit_course_id Org Unit Course ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($org_unit_course_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($org_unit_course_id),
        ]);
    }

    /**
     * Creates a new OrgUnitCourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgUnitCourse();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Unit Course Created!');
                return $this->redirect(['index']);
               // return $this->redirect(['view', 'org_unit_course_id' => $model->org_unit_course_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgUnitCourse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $org_unit_course_id Org Unit Course ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($org_unit_course_id)
    {
        $model = $this->findModel($org_unit_course_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Unit Course Updated!');
            return $this->redirect(['index']);
            //return $this->redirect(['view', 'org_unit_course_id' => $model->org_unit_course_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgUnitCourse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $org_unit_course_id Org Unit Course ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($org_unit_course_id)
    {
        $this->findModel($org_unit_course_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgUnitCourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $org_unit_course_id Org Unit Course ID
     * @return OrgUnitCourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($org_unit_course_id)
    {
        if (($model = OrgUnitCourse::findOne(['org_unit_course_id' => $org_unit_course_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
