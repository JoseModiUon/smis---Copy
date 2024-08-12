<?php

namespace app\modules\examManagement\models\search;

use app\modules\feesManagement\models\AcademicProgress;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;


/**
 * AcademicProgressSearch represents the model behind the search form of `app\modules\feesManagement\models\AcademicProgress`.
 */
class AcademicProgressSearch extends AcademicProgress
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
     * Creates data provider instance with search query applied
     * @param $regNumber
     * @return ActiveDataProvider
     */
    public function search($regNumber): ActiveDataProvider
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

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
