<?php

namespace app\modules\studentRecords\controllers;

use app\models\AdmittedStudent;
use app\modules\studentRecords\models\search\AdmissionReportSearch;
use Throwable;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * AdmissionReportController implements the CRUD actions for AdmittedStudent model.
 */
class AdmissionReportController extends Controller
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
     * Lists all AdmittedStudent models.
     *
     * @return string
     */
    public function actionIndex()
    {
        try {
            $intake_data = AdmittedStudent::find()->select(
                [
                    'sm_intakes.intake_name'
                ]
            )
                ->innerJoinWith(['intakeCode'])
                ->groupBy(['sm_intakes.intake_name'])
                ->asArray()->all();

            $preCount = AdmittedStudent::find()->alias("AS")->select([
                "AS.admission_status", "COUNT(*) student_count",
                'sm_intakes.intake_name'
            ])
                ->innerJoinWith(['intakeCode'])
                ->andFilterWhere(['admission_status' => 'PRE-REGISTRATION'])
                ->groupBy("AS.admission_status, sm_intakes.intake_name")->asArray()->all();

            $registeredCount = AdmittedStudent::find()->alias("AS")->select([
                "AS.admission_status", "COUNT(*) student_count",
                'sm_intakes.intake_name'
            ])
                ->innerJoinWith(['intakeCode'])
                ->andFilterWhere(['admission_status' => 'REGISTERED'])
                ->groupBy("AS.admission_status, sm_intakes.intake_name")->asArray()->all();

            $dataProvider = new ArrayDataProvider([
                'allModels' => $intake_data,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            return $this->render('index', [
                'registeredCount' => $registeredCount,
                'preRegisteredcount' => $preCount,
                'dataProvider' => $dataProvider,
                'title' => 'Intake Admission Report'
            ]);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**In summary, this code defines a method for generating a program report 
     * that displays the number of students with "PRE-REGISTRATION" and "REGISTERED" 
     * admission statuses, grouped by program full name.  */

    public function actionProgReport($intake_name)
    {
        try {
            $searchModel = new AdmissionReportSearch();
            $searchModel->intake_name = $intake_name;
            $dataProvider = $searchModel->search($this->request->queryParams);

            $preCount = AdmittedStudent::find()->alias("AS")->select([
                "AS.admission_status", "COUNT(*) student_count",
                'org_programmes.prog_full_name',
            ])
                ->innerJoinWith(['studentProgrammeCurriculums' => function (ActiveQuery $spc) {
                    $spc->innerJoinWith(['progCurriculum' => function (ActiveQuery $pc) {
                        $pc->innerJoinWith('prog');
                    }]);
                }])
                ->andFilterWhere(['admission_status' => 'PRE-REGISTRATION'])
                ->groupBy("AS.admission_status, org_programmes.prog_full_name")->asArray()->all();

            $registeredCount = AdmittedStudent::find()->alias("AS")->select([
                "AS.admission_status", "COUNT(*) student_count",
                'org_programmes.prog_full_name',
            ])
                ->innerJoinWith(['studentProgrammeCurriculums' => function (ActiveQuery $spc) {
                    $spc->innerJoinWith(['progCurriculum' => function (ActiveQuery $pc) {
                        $pc->innerJoinWith('prog');
                    }]);
                }])
                ->andFilterWhere(['admission_status' => 'REGISTERED'])
                ->groupBy("AS.admission_status, org_programmes.prog_full_name")->asArray()->all();


            return $this->render('prog-report', [
                'registeredCount' => $registeredCount,
                'preRegisteredcount' => $preCount,
                'intake' => $intake_name,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'title' => 'Programme Admission Report'

            ]);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Finds the AdmittedStudent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $adm_refno Adm Refno
     * @return AdmittedStudent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($adm_refno)
    {

        if (($model = AdmittedStudent::findOne(['adm_refno' => $adm_refno])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
