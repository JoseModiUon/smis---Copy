<?php
/** author Jeff Wahome <wahome4jeff@gmail.com> */

namespace app\modules\examManagement\controllers;

use app\models\CrProgCurrTimetable;
use app\modules\courseRegistration\models\search\IndividualExamListSearch;
use app\modules\examManagement\models\search\IndividualMarksListSearch;
use app\modules\examManagement\models\search\MarksListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * MArksEntryListController implements the CRUD actions for ExMarksheet model.
 */
class MarksEntryListController extends Controller
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
            $searchModel = new MarksListSearch();
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
    public function actionView()
    {
        try{
            $searchModel = new IndividualMarksListSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
        
            return $this->render('view', [
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
