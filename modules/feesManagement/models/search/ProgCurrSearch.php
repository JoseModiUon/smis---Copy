<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/12/2024
 * @time: 11:41 AM
 */

namespace app\modules\feesManagement\models\search;

use app\modules\studentRegistration\models\ProgrammeCurriculum;
use yii\data\ActiveDataProvider;
use yii\db\Query;

final class ProgCurrSearch extends ProgrammeCurriculum
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'prog_code',
            'prog_short_name',
            'billing_type_desc'
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
                    'prog_code',
                    'prog_short_name',
                    'billing_type_desc'
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
                'pc.prog_curriculum_id',
                'pg.prog_code',
                'pg.prog_short_name',
                'bt.billing_type_desc'
            ])
            ->from(parent::tableName() . ' pc')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programmes pg', 'pg.prog_id=pc.prog_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_billing_type bt', 'bt.billing_type_id=pc.billing_type_id')
            ->where(['pc.status' => 'ACTIVE']);

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

        $query->andFilterWhere(['like', 'pg.prog_code', $this->getAttribute('prog_code')]);
        $query->andFilterWhere(['like', 'pg.prog_short_name', $this->getAttribute('prog_short_name')]);
        $query->andFilterWhere(['like', 'bt.billing_type_desc', $this->getAttribute('billing_type_desc')]);

        return $dataProvider;
    }
}