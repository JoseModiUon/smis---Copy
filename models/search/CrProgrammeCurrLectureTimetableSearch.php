<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrProgrammeCurrLectureTimetable;

/**
 * CrProgrammeCurrLectureTimetableSearch represents the model behind the search form of `app\models\CrProgrammeCurrLectureTimetable`.
 */
class CrProgrammeCurrLectureTimetableSearch extends CrProgrammeCurrLectureTimetable
{
    public $room;
    public $day;
    public $class;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lecture_timetable_id', 'timetable_id', 'lecture_room_id', 'day_id', 'class_code'], 'integer'],
            [['start_time', 'end_time', 'room', 'day', 'class'], 'safe'],
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
    public function search($params, $moreParams = [])
    {
        $query = CrProgrammeCurrLectureTimetable::find();

        $query->joinWith(['day', 'room', 'classGroups']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['room'] = [

            'asc' => ['org_rooms.room_name' => SORT_ASC],
            'desc' => ['org_rooms.room_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['day'] = [

            'asc' => ['org_days.day_name' => SORT_ASC],
            'desc' => ['org_days.day_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['class'] = [

            'asc' => ['cr_class_groups.class_description' => SORT_ASC],
            'desc' => ['cr_class_groups.class_description' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'lecture_timetable_id' => $this->lecture_timetable_id,
            'timetable_id' => $this->timetable_id,
            'lecture_room_id' => $this->lecture_room_id,
            'day_id' => $this->day_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'class_code' => $this->class_code,
        ]);

        if (!empty($moreParams)) {
            $query->andFilterWhere([
                'timetable_id' => $moreParams['timetable_id']
            ]);
        }
        $query
            ->andFilterWhere(['ilike', 'smis.org_rooms.room_name', $this->room])
            ->andFilterWhere(['ilike', 'smis.org_days.day_name', $this->day])
            ->andFilterWhere(['ilike', 'smis.cr_class_groups.class_description', $this->class]);
        return $dataProvider;
    }
}
