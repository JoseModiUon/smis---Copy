<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/9/2024
 * @time: 8:23 PM
 */

namespace app\modules\feesManagement\models\search;

use app\modules\feesManagement\models\FeeItem;
use yii\data\ActiveDataProvider;
use yii\db\Query;

final class ItemChargesSearch extends FeeItem
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'fee_description',
                ],
                'safe'
            ]
        ];
    }

    /**
     * @param array $queryParams
     * @return ActiveDataProvider
     */
    public function search(array $queryParams): ActiveDataProvider
    {
        $query = (new Query())->from(parent::tableName());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false
        ]);

        $this->load($queryParams);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'fee_description', $this->fee_description]);

        return $dataProvider;
    }
}