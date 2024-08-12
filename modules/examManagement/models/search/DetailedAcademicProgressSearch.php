<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 7/29/2024
 * @time: 12:42 PM
 */

namespace app\modules\examManagement\models\search;

use app\modules\studentRegistration\models\StudentSemesterSessionProgress;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class DetailedAcademicProgressSearch extends StudentSemesterSessionProgress
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $regNumber
     * @return ActiveDataProvider
     */
    public function search($regNumber): ActiveDataProvider
    {
        $query = (new Query())
            ->select([
                'ssssp.student_semester_session_id',
                'oas.acad_session_name',
                'oal.academic_level_name',
                'sss.status as student_status',
                'ssssp.promotion_status', // if level is 1.1 it will be REGISTERED, PROMOTED otherwise
                'ass.semester_code',
                'ass.acad_session_semester_desc',
                'st.semester_type',
                'sc.study_centre_name',
                'sg.study_group_name',
                'ssssp.registration_date',

            ])
            ->from(SMIS_DB_SCHEMA . '.sm_student_sem_session_progress ssssp')
            ->innerJoin(SMIS_DB_SCHEMA . '.sm_academic_progress sap', 'ssssp.academic_progress_id = sap.academic_progress_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session oas', 'sap.acad_session_id = oas.acad_session_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_levels oal', 'oal.academic_level_id = sap.academic_level_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_programme_curriculum sspc', 'sspc.student_prog_curriculum_id = sap.student_prog_curriculum_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_status sss', 'sss.status_id = sspc.status_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_prog_curr_semester_group pcsg', 'pcsg.prog_curriculum_sem_group_id =ssssp.prog_curriculum_semester_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_prog_curr_semester pcs', 'pcs.prog_curriculum_semester_id = pcsg.prog_curriculum_semester_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session_semester ass', 'ass.acad_session_semester_id = pcs.acad_session_semester_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_semester_type st', 'st.semester_type_id = pcs.semester_type_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_study_centre_group scg', 'scg.study_centre_group_id = pcsg.study_centre_group_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_study_centre sc', 'sc.study_centre_id = scg.study_centre_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_study_group sg', 'sg.study_group_id = scg.study_group_id')
            ->where(['sspc.registration_number' => $regNumber])
            ->orderBy(['ssssp.student_semester_session_id' => SORT_ASC]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false
        ]);
    }
}