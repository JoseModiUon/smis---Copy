<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrProgrammeCurrLectureTimetable;
use Yii;
use yii\data\SqlDataProvider;
use yii\db\Query;

/**
 * CrProgrammeCurrLectureTimetableSearch represents the model behind the search form of `app\models\CrProgrammeCurrLectureTimetable`.
 */
class CrProgrammeCurrLectureTimetableDisplaySearch extends CrProgrammeCurrLectureTimetable
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


        $query = "
        select lecture_room_id,
        max(case when (day_id='1') then start_time else NULL end) as Sunday,
        max(case when (day_id='2') then concat(start_time,' - ',end_time) else NULL end) as Monday,
        max(case when (day_id='3') then concat(start_time,' - ',end_time) else NULL end) as Tuesday,
        max(case when (day_id='4') then concat(start_time,' - ',end_time) else NULL end) as Wednesday,
        max(case when (day_id='5') then concat(start_time,' - ',end_time) else NULL end) as Thursday,
        max(case when (day_id='6') then concat(start_time,' - ',end_time) else NULL end) as Friday,
        max(case when (day_id='7') then concat(start_time,' - ',end_time) else NULL end) as Saturday
     from smis.cr_programme_curr_lecture_timetable
     group by lecture_room_id
     order by lecture_room_id
    ";

        $sql2 = "
    select lecture_room_id, timetable_id ,class_code,
         max(case when (day_id='1') then start_time else NULL end) as Sunday,
	   max(case when (day_id='2') then concat(start_time,' - ',end_time) else NULL end) as Monday,
	   max(case when (day_id='3') then concat(start_time,' - ',end_time) else NULL end) as Tuesday,
	   max(case when (day_id='4') then concat(start_time,' - ',end_time) else NULL end) as Wednesday,
	   max(case when (day_id='5') then concat(start_time,' - ',end_time) else NULL end) as Thursday,
	   max(case when (day_id='6') then concat(start_time,' - ',end_time) else NULL end) as Friday
         from smis.cr_programme_curr_lecture_timetable
         group by lecture_room_id,timetable_id,class_code 
         order by lecture_room_id, timetable_id ,class_code ";
        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query,
        // ]);

        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            'totalCount' => count(Yii::$app->getDb()->createCommand($query)->queryAll()),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => false,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        // $query->andFilterWhere([
        //     'lecture_timetable_id' => $this->lecture_timetable_id,
        //     'timetable_id' => $this->timetable_id,
        //     'lecture_room_id' => $this->lecture_room_id,
        //     'day_id' => $this->day_id,
        //     'start_time' => $this->start_time,
        //     'end_time' => $this->end_time,
        //     'class_code' => $this->class_code,
        // ]);

        // if (!empty($moreParams)) {
        //     $query->andFilterWhere([
        //         'timetable_id' => $moreParams['timetable_id']
        //     ]);
        // }
        // $query
        //     ->andFilterWhere(['ilike', 'smis.org_rooms.room_name', $this->room])
        //     ->andFilterWhere(['ilike', 'smis.org_days.day_name', $this->day])
        //     ->andFilterWhere(['ilike', 'smis.cr_class_groups.class_description', $this->class]);
        return $dataProvider;
    }
}
