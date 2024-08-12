<?php
/**
 * @author Githaiga Maina <githaigamaina@uonbi.ac.ke>
 * @date: 7/17/2023
 * @time: 12:18 PM
 */

namespace app\modules\examManagement\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ExamProcessing extends Model
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
          'progCurriculum.programme.prog_code',
          'progCurriculum.programme.prog_full_name'
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
              'progCurriculum.programme.prog_code',
              'progCurriculum.programme.prog_full_name'
            ],
            'safe'
          ]
        ];
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
     * @param array $params
     * @param array $moreParams
     * @return ActiveDataProvider
     */
    public function search(array $params, array $moreParams = []): ActiveDataProvider
    {
        $query = ProgCurriculumUnit::find()->alias('pu')
          ->select([
            'pu.prog_curriculum_unit_id',
            'pu.org_unit_history_id',
            'pu.prog_curriculum_id'
          ])
          ->where(['pu.status' => 'ACTIVE'])
          ->joinWith(['progCurriculum pc' => function (ActiveQuery $q) {
              $q->select([
                'pc.prog_curriculum_id',
                'pc.prog_id',
              ]);
          }], true, 'INNER JOIN')
          ->andWhere(['pc.status' => 'ACTIVE'])
          ->joinWith(['progCurriculum.programme pg' => function (ActiveQuery $q) {
              $q->select([
                'pg.prog_id',
                'pg.prog_code',
                'pg.prog_full_name'
              ]);
          }], true, 'INNER JOIN')
          ->andWhere(['pg.status' => 'ACTIVE'])
          ->joinWith(['unitHistory uh' => function (ActiveQuery $q) {
              $q->select([
                'uh.org_unit_history_id',
                'uh.org_unit_id'
              ]);
          }], true, 'INNER JOIN')
          ->joinWith(['unitHistory.unit un' => function (ActiveQuery $q) {
              $q->select([
                'un.unit_id',
                'un.unit_code',
                'un.unit_name'
              ]);
          }], true, 'INNER JOIN')
          ->asArray();

        $dataProvider = new ActiveDataProvider([
          'query' => $query,
          'sort' => false,
          'pagination' => [
            'pagesize' => 50,
          ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'pg.prog_code', $this->getAttribute('progCurriculum.programme.prog_code')]);
        $query->andFilterWhere(['like', 'pg.prog_full_name', $this->getAttribute('progCurriculum.programme.prog_full_name')]);

        return $dataProvider;
    }
}
