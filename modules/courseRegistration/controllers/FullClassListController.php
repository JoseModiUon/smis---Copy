  <?php

namespace app\modules\courseRegistration\controllers;

use app\modules\courseRegistration\models\CrProgCurrTimetable;
use app\modules\courseRegistration\models\ProgCurrTimetable;
use app\modules\courseRegistration\models\search\FullClassListSearch;
use app\modules\courseRegistration\models\search\ClassListSearch;
// use app\modules\courseRegistration\models\search\IndividualClassListSearch;
use app\modules\courseRegistration\models\search\IndividualClassListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\db\Query;
use yii\filters\VerbFilter;
use Yii;

/**
 * FullClassListController implements the CRUD actions for CrProgCurrTimetable model.
 */
class FullClassListController extends Controller
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
     * Lists all CrProgCurrTimetable models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FullClassListSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewNew()
    {
       
        $searchModel = new IndividualClassListSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        

        // dd($dataProvider->getModels());

        return $this->render('view_new', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CrProgCurrTimetable model.
     * @param int $timetable_id Timetable ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($timetable_id)
    {   
        $searchModel = new IndividualClassListSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        

        // dd($dataProvider->getModels());

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $this->findModel($timetable_id),
        ]);
    }

    /**
     * Creates a new CrProgCurrTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CrProgCurrTimetable();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'timetable_id' => $model->timetable_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CrProgCurrTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $timetable_id Timetable ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($timetable_id)
    {
        $model = $this->findModel($timetable_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'timetable_id' => $model->timetable_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CrProgCurrTimetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $timetable_id Timetable ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($timetable_id)
    {
        $this->findModel($timetable_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CrProgCurrTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $timetable_id Timetable ID
     * @return CrProgCurrTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($timetable_id)
    {
        if (($model = CrProgCurrTimetable::findOne(['timetable_id' => $timetable_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
