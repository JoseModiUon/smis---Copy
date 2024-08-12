<?php

namespace app\modules\courseRegistration\controllers;

use app\models\OrgProgCurrSemester;
use app\models\search\OrgProgCurrSemesterSearch;
use app\modules\setup\utils\AutoSynchronize;
use app\modules\setup\utils\DuplicateChecker;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * OrgProgCurrSemesterController implements the CRUD actions for OrgProgCurrSemester model.
 */
class OrgProgCurrSemesterController extends Controller
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
     * Lists all OrgProgCurrSemester models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrSemesterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCurrSemester model.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_semester_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_semester_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrSemester model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrSemester();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {

                $dpCheck = new DuplicateChecker();
                $dpCheck->insert(OrgProgCurrSemester::class, $this, [

                    ['prog_curriculum_id' => $this->request->post('OrgProgCurrSemester')['prog_curriculum_id']],
                    ['acad_session_semester_id' => $this->request->post('OrgProgCurrSemester')['acad_session_semester_id']],
                    ['semester_type_id' => $this->request->post('OrgProgCurrSemester')['semester_type_id']],
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
     * Updates an existing OrgProgCurrSemester model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_semester_id)
    {
        $model = $this->findModel($prog_curriculum_semester_id);

        if ($this->request->isPost && $this->assign($model)) {
            if ($model->save()) {
                $autoSync = new AutoSynchronize();
                if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                }
            }
            var_dump($model->getErrors());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function assign($modelObj)
    {
        $name = (new \ReflectionClass(new $modelObj))->getShortName();


        foreach ($this->request->post()[$name] as $key => $value) {
            if (in_array($key, $modelObj->attributes())) {
                $modelObj->$key = $value;
            }
        }

        return empty(array_diff(array_keys($this->request->post()[$name]), $modelObj->attributes()));
    }
    /**
     * Deletes an existing OrgProgCurrSemester model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_semester_id)
    {
        $this->findModel($prog_curriculum_semester_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrSemester model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return OrgProgCurrSemester the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_semester_id)
    {
        if (($model = OrgProgCurrSemester::findOne(['prog_curriculum_semester_id' => $prog_curriculum_semester_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
