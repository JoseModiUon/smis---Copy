<?php
/** author Jeff Wahome <wahome4jeff@gmail.com> */

namespace app\modules\courseRegistration\controllers;

use app\models\CrProgCurrTimetable;
use app\modules\courseRegistration\models\search\FullExamListSearch;
use app\modules\courseRegistration\models\search\IndividualExamListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * FullExamListController implements the CRUD actions for CrProgCurrTimetable model.
 */
class FullExamListController extends Controller
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
        try{
            $searchModel = new FullExamListSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        }catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }   
    }   

    /**
     * Displays a single CrProgCurrTimetable model.
     * @param int $timetable_id Timetable ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewNew()
    {
        try{
            $searchModel = new IndividualExamListSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
        
            return $this->render('view_new', [
               'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
            ]);

        }
        catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
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
