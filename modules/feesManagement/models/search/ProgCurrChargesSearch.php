<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/8/2024
 * @time: 9:14 PM
 */

namespace app\modules\feesManagement\models\search;

use app\modules\feesManagement\models\ProgCurrCharge;
use yii\data\ActiveDataProvider;
use yii\db\Query;

final class ProgCurrChargesSearch extends ProgCurrCharge
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'acad_session_name',
            'fee_description'
        ]);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'acad_session_name',
                    'level_of_study',
                    'semester',
                    'fee_description'
                ],
                'safe'
            ]
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = (new Query())
            ->select([
                'pcc.charge_type_id',
                'pcc.amount_charged',
                'pcc.start_date',
                'pcc.end_date',
                'sess.acad_session_name',
                'pg.prog_code',
                'pg.prog_short_name',
                'pcc.level_of_study',
                'pcc.semester',
                'bf.name as billing_freq',
                'fi.fee_description',
                'fi.publish'
            ])
            ->from(parent::tableName() . ' pcc')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session sess', 'sess.acad_session_id=pcc.acad_session_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programme_curriculum pc', 'pc.prog_curriculum_id=pcc.prog_curr_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programmes pg', 'pg.prog_id=pc.prog_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_billing_frequency bf', 'bf.billing_frequency_id=pcc.billing_frequency_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_fee_items fi', 'fi.fee_code=pcc.fee_code')
            ->where(['pcc.prog_curr_id' => $params['progCurrId']])
            ->orderBy([
                'sess.acad_session_name' => SORT_DESC,
                'pcc.level_of_study' => SORT_ASC,
                'pcc.semester' => SORT_ASC
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ]
        ]);

        $this->load($params['queryParams']);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['level_of_study' => $this->level_of_study]);
        $query->andFilterWhere(['semester' => $this->semester]);
        $query->andFilterWhere(['like', 'sess.acad_session_name', $this->getAttribute('acad_session_name')]);
        $query->andFilterWhere(['like', 'fi.fee_description', $this->getAttribute('fee_description')]);

        return $dataProvider;
    }
}