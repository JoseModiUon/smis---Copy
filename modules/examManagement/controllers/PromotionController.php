<?php
// file created by Jeff Rureri <wahome4jeff@gmail.com>

namespace app\modules\examManagement\controllers;

use app\helpers\AuditTrail;
use app\models\generated\AcademicLevel;
use app\models\OrgAcademicLevels;
use app\models\OrgAcademicSession;
use app\models\OrgProgCurrSemester;
use app\models\OrgProgCurrSemesterGroup;
use app\models\OrgSemesterCode;
use app\models\SmAcademicProgress;
use app\models\SmExamResult;
use app\models\SmStudentProgrammeCurriculum;
use app\models\SmStudentSemesterSessionStatus;
use app\models\SmStudentSemSessionProgress;
use app\modules\examManagement\models\search\PromotionSearch;
use app\modules\studentRegistration\models\AcademicProgress;
use Exception;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

use app\traits\ControllerTrait;


use function PHPSTORM_META\map;




/**
 * PromotionController implements the CRUD actions for SmExamResult model.
 */
class PromotionController extends BaseController
{
    /**
     * @inheritDoc
     */

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ]
            ],
        );
    }

    /**
     * Lists all SmExamResult models.
     *
     * @return string
     */
    public function actionIndex()
    {
        try {
            $searchModel = new PromotionSearch();
            $is_searched = $this->request->queryParams;
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'title' => $this->createPageTitle("Student to promote"),
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'is_searched' => $is_searched,
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }


    /**
     * Displays a single SmExamResult model.
     * @param int $exam_result_id Exam Result ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    /** 
     * Function to promote student to next level
     * @param int $exam_result_id Exam Result ID
     * @return string
     */

    public function actionPromoteStudent()
    {
        try {
            $url = $this->request->queryParams; //params from the search
            $result_ids = Yii::$app->request->post('examResultID');

            $student_count = 0;

            foreach ($result_ids as $result_id) {
                $student_data = SmExamResult::find()->select(['fk_registration_number', 'result'])->where(['exam_result_id' => $result_id])->asArray()->one();

                $response = $this->actionProgression($student_data['fk_registration_number'], $student_data['result']);

                if ($response) {
                    $student_count++;
                } else {
                    $this->setFlash('danger', 'Promoting Students', 'Error occured when promoting student!');
                    return $this->redirect(['/exam-management/promotion', ...$url]);
                }
            }

            $student_count <= 1 ? $word = " student" : $word = " students";

            $this->setFlash('success', 'Promoting Students', 'You have promoted ' . $student_count . '' . $word . ' successfully!');
            return $this->redirect(['/exam-management/promotion', ...$url]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
    

    private function actionProgression($registration_number, $result)
    {
        try {
            //First find the Student Programme Curriculum ID
            $student_curriculum_id = SmStudentProgrammeCurriculum::find()
                ->select(['student_prog_curriculum_id'])->where(['registration_number' => $registration_number])
                ->asArray()->one();


            //Find current Academic Progress
            $academic_progress = AcademicProgress::find()->select(['academic_progress_id'])
                ->where(['student_prog_curriculum_id' => $student_curriculum_id['student_prog_curriculum_id']])
                ->orderBy(['academic_progress_id' => 'DESC'])
                ->asArray()
                ->one();

            //Find current Semester Progress
            $student_data_progress = SmStudentSemSessionProgress::find()
                ->select(['student_semester_session_id'])
                ->where(['academic_progress_id' => $academic_progress['academic_progress_id']])
                ->asArray()->one();

            //Current Student Data

            $student_data = (new Query())
                ->select([
                    'osc.semester_code',
                    'opc.annual_semesters',
                    'oas.acad_session_id',
                    'opl.programme_level_id',
                    'ost.semester_type'
                ])
                ->from('smis.sm_student_sem_session_progress sssp')
                ->where(['sssp.student_semester_session_id' => $student_data_progress['student_semester_session_id']])
                ->innerJoin('smis.org_prog_curr_semester opcs', 'opcs.prog_curriculum_semester_id=sssp.prog_curriculum_semester_id')
                ->innerJoin('smis.org_prog_curr_semester_group opcsg', 'opcsg.prog_curriculum_semester_id=opcs.prog_curriculum_semester_id')
                ->innerJoin('smis.org_prog_level opl', 'opl.programme_level_id=opcsg.programme_level')
                ->innerJoin('smis.org_programme_curriculum opc', 'opc.prog_curriculum_id=opcs.prog_curriculum_id')
                ->innerJoin('smis.org_semester_type ost', 'ost.semester_type_id=opcs.semester_type_id')
                ->innerJoin('smis.org_academic_session_semester oass', 'oass.acad_session_semester_id=opcs.acad_session_semester_id')
                ->innerJoin('smis.org_academic_session oas', 'oas.acad_session_id=oass.acad_session_id')
                ->innerJoin('smis.org_semester_code osc', 'osc.semester_code=oass.semester_code')
                ->one();

            //Identify student markers
            $student_markers = (new Query())->select([
                'osc.study_centre_id',
                'opc.prog_curriculum_id',
                'op.prog_code',
                'opl.programme_level_id',
                'osc2.semester_code',
                'oas.acad_session_id'
            ])->from(['smis.org_prog_curr_semester_group opcsg'])
                ->innerJoin('smis.org_prog_level opl', 'opl.programme_level_id=opcsg.programme_level')
                ->where(['opl.programme_level_id' => $student_data['programme_level_id']])
                ->innerJoin('smis.org_study_centre_group oscg', 'oscg.study_centre_group_id=opcsg.study_centre_group_id')
                ->innerJoin('smis.org_study_centre osc', 'osc.study_centre_id=oscg.study_centre_id')
                ->innerJoin('smis.org_prog_curr_semester opcs', 'opcs.prog_curriculum_semester_id=opcsg.prog_curriculum_semester_id')
                ->innerJoin('smis.org_academic_session_semester oass', 'oass.acad_session_semester_id=opcs.acad_session_semester_id')
                ->innerJoin('smis.org_semester_code osc2', 'osc2.semester_code=oass.semester_code')
                ->andWhere(['osc2.semester_code' => $student_data['semester_code']])
                ->innerJoin('smis.org_academic_session oas', 'oas.acad_session_id=oass.acad_session_id')
                ->innerJoin('smis.org_programme_curriculum opc', 'opc.prog_curriculum_id=opcs.prog_curriculum_id')
                ->innerJoin('smis.org_programmes op', 'op.prog_id=opc.prog_id')
                ->innerJoin('smis.sm_student_programme_curriculum sspc', 'sspc.prog_curriculum_id=opc.prog_curriculum_id')
                ->andWhere(['sspc.registration_number' => $registration_number])
                ->one();

            $academic_level = $student_data['programme_level_id'];

            $acad_session_id = $student_markers['acad_session_id'];
            $semester_code = $student_markers['semester_code'];


            if ($student_data['semester_code'] == $student_data['annual_semesters']) {
                if ($result == "PASS") {
                    // proceed to next academic year, level, sem 1
                    $acad_session_id = $this->getNextAcademicYear($acad_session_id);

                    $academic_level = $academic_level + 1;
                    $academic_level_id = $this->getNextAcademicLevel($academic_level);

                    $semester_code = 1;
                } else {
                    // proceed to next academic year and maintain current level and sem
                    $acad_session_id = $this->getNextAcademicYear($acad_session_id);
                    $academic_level_id = $this->getNextAcademicLevel($academic_level);
                }
            } else {
                /**
                 *  proceed to nex sem without checking recommendation
                 */
                $semester_code = $student_markers['semester_code'] + 1;
                $academic_level_id = $this->getNextAcademicLevel($academic_level);
            }

            //Getting the right next programme_curricullum_semester
            $prog_curriculum_semester_id = (new Query())->select(
                [
                    'opcs.prog_curriculum_semester_id',
                    'ost.semester_type',
                    'opc.prog_curriculum_id',
                    'oas.acad_session_id',
                    'osc.semester_code'
                ]
            )->from(['smis.org_prog_curr_semester opcs'])
                ->innerJoin('smis.org_semester_type ost', 'ost.semester_type_id=opcs.semester_type_id')
                ->where(['ost.semester_type' => 'TEACHING'])
                ->innerJoin('smis.org_programme_curriculum opc', 'opc.prog_curriculum_id=opcs.prog_curriculum_id')
                ->andWhere(['opc.prog_curriculum_id' => $student_markers['prog_curriculum_id']])
                ->innerJoin('smis.org_academic_session_semester oass', 'oass.acad_session_semester_id=opcs.acad_session_semester_id')
                ->innerJoin('smis.org_academic_session oas', 'oas.acad_session_id=oass.acad_session_id')
                ->andWhere(['oas.acad_session_id' => $acad_session_id])
                ->innerJoin('smis.org_semester_code osc', 'osc.semester_code=oass.semester_code')
                ->andWhere(['osc.semester_code' => $semester_code])
                ->one();

            $response = $this->promoteStudent($acad_session_id, $academic_level_id, $student_curriculum_id['student_prog_curriculum_id'], $prog_curriculum_semester_id['prog_curriculum_semester_id']);

            return $response;
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    private function promoteStudent($acad_session_id, $academic_level_id, $student_prog_curriculum_id, $prog_curriculum_semester_id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $acad_ok = false;
            $sem_ok = false;

            //Constants. They don't change when promoting or not
            $progress_status = 1;
            $current_status = 1;
            $sem_progress_number = 1;
            $prom_status_id = 2;
            $session_status = SmStudentSemesterSessionStatus::find()
                ->select(['*'])
                ->where(['status_name' => 'ACTIVE'])
                ->asArray()->one();

            /**functionality to promote students in academic level*/
            $academic_model = new AcademicProgress();

            $academic_model->acad_session_id = $acad_session_id;
            $academic_model->academic_level_id = $academic_level_id;
            $academic_model->student_prog_curriculum_id = $student_prog_curriculum_id;
            $academic_model->progress_status_id = $progress_status;
            $academic_model->current_status = $current_status;

            $trial = new AuditTrail();
            $trial->main($academic_model, 'INSERT');
            if ($academic_model->save()) {
                $acad_ok = true;
            }


            /** functionality to promote students in semester */
            $session_model = new SmStudentSemSessionProgress();
            // dd($session_model->attributes());
            $session_model->prog_curriculum_semester_id = $prog_curriculum_semester_id;
            $session_model->academic_progress_id = $academic_model->academic_progress_id;
            $session_model->rep_status_id = $session_status['status_id'];
            $session_model->sem_progress_number = $sem_progress_number;
            $session_model->prom_status_id = $prom_status_id;

            $trial = new AuditTrail();
            $trial->main($session_model, 'INSERT');

            if ($session_model->save()) {
                $sem_ok = true;
            }

            $ok = false;

            if ($acad_ok && $sem_ok) {
                $ok = true;
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }

            return $ok;
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    private function getNextAcademicLevel(int $academic_level)
    {
        try {
            $academic_level_id = OrgAcademicLevels::find()
                ->select(['org_academic_levels.academic_level_id'])
                ->where(['org_academic_levels.academic_level' => $academic_level])
                ->asArray()->one();
            return $academic_level_id['academic_level_id'];
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    private function getNextAcademicYear(int $acad_session_id)
    {
        try {

            //Change to next academic session 
            $academic_year = OrgAcademicSession::find()->select(['org_academic_session.acad_session_name'])
                ->where(['org_academic_session.acad_session_id' => $acad_session_id])
                ->asArray()->one();

            $sess_name = explode('/', $academic_year['acad_session_name']);
            $first_part = $sess_name[0];
            $first_part++;
            $second_part = $sess_name[1];
            $second_part++;
            $new_academic_session_name = $first_part . '/' . $second_part;

            $new_academic_session = OrgAcademicSession::find()
                ->select(['org_academic_session.acad_session_id'])
                ->where(['org_academic_session.acad_session_name' => $new_academic_session_name])
                ->asArray()->one();

            if ($new_academic_session != null) {
                return $new_academic_session['acad_session_id'];
            } else {
                $msg = 'Academic Session(' . $new_academic_session_name . ') student is promoted to is not available. Create the session before trying again promoting to the session!';
                return dd($msg);
                // $this->setFlash('danger', 'Academic Session', $msg);
                // return $this->redirect(['/exam-management/promotion', ...$url]);
            }
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Finds the SmExamResult model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $exam_result_id Exam Result ID
     * @return SmExamResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($exam_result_id)
    {
        if (($model = SmExamResult::findOne(['exam_result_id' => $exam_result_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
