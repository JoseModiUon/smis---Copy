<?php

namespace app\modules\studentRecords\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\AcademicProgress;
use Yii;
use yii\db\Query;


/**
 * AcademicProgressSearch represents the model behind the search form of `app\modules\feesManagement\models\AcademicProgress`.
 */
class AcademicProgressSearch extends AcademicProgress
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($regNumber)
    {
        $query = (new Query())
        ->select([
            'oas.acad_session_name',
            'oal.academic_level_name',
            'sss.status',
            'sap.progress_status_id',
            'sspc.registration_number'
        ])
        ->from(SMIS_DB_SCHEMA . '.sm_academic_progress sap')
        ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session oas', 'sap.acad_session_id = oas.acad_session_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_levels oal', 'sap.academic_level_id = oal.academic_level_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_programme_curriculum sspc', 'sap.student_prog_curriculum_id = sspc.student_prog_curriculum_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_status sss', 'sspc.status_id = sss.status_id')
        ->where(['sspc.registration_number' => $regNumber]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }



    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function detailed($regNumber)
    {
        $query = (new Query())
        ->select([
        'ssssp.registration_date',
        'ssssp.semester_progress',
        'ssssp.promotion_status',
        'sspc.registration_number',
        'saps.progress_status_name',
        'oal.academic_level_name',
        'ssc.std_category_name',
        'sss.current_status',
        'sas.national_id'
        ])
        ->from(SMIS_DB_SCHEMA . '.sm_student_sem_session_progress ssssp')
        ->innerJoin(SMIS_DB_SCHEMA . '.smis.sm_academic_progress sap', 'ssssp.academic_progress_id = sap.academic_progress_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.smis.sm_academic_progress_status saps', 'saps.progress_status_id = sap.progress_status_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.smis.org_academic_levels oal', 'oal.academic_level_id = sap.academic_level_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_programme_curriculum sspc', 'sspc.student_prog_curriculum_id = sap.student_prog_curriculum_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_category ssc', 'ssc.std_category_id = sspc.student_category_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.smis.sm_student_status sss', 'sss.status_id = sspc.status_id')
        ->innerJoin(SMIS_DB_SCHEMA . '.sm_admitted_student sas', 'sas.adm_refno = sspc.adm_refno')
        ->where(['sspc.registration_number' => $regNumber]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }    
}
