<?php

namespace app\modules\courseRegistration\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrProgCurrTimetable;
use app\modules\studentRegistration\models\AcademicSession;
use kartik\form\ActiveForm;
use yii\db\ActiveQuery;

/**
 * ProgStudentAnalysisSearch represents the model behind the search form of `app\models\CrProgCurrTimetable`.
 */
class ProgStudentAnalysisSearch extends CrProgCurrTimetable
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode', 'publish_status'], 'integer'],
            [['exam_date', 'mrksheet_id', 'userid', 'ip_address', 'user_action', 'last_update'], 'safe'],
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
    public function search($params)
    {
        $query = CrProgCurrTimetable::find()->select([
            'org_academic_session.acad_session_id',
            'cr_prog_curr_timetable.timetable_id',
            'org_academic_session.acad_session_name',
            'org_programme_curriculum.prog_curriculum_desc',
            'org_programme_curriculum.prog_curriculum_id'
        ])
            ->joinWith(['progCurriculumSemGroup' => function (ActiveQuery $pcsg) {
                $pcsg->innerJoinWith(['progCurriculumSemester' => function (ActiveQuery $pcs) {
                    $pcs->innerJoinWith(['progCurriculum' => function (ActiveQuery $pc) {
                        $pc->innerJoinWith(['orgProgCurrCourses' =>function (ActiveQuery $opcc) {
                            $opcc->innerJoinWith('course');
                        }]);
                    }]);
                    $pcs->innerJoinWith(['acadSessionSemester' => function (ActiveQuery $ass) {
                        $ass->innerJoinWith('acadSession');
                    }]);
                }]);
            }])
            ->distinct();
        // $query = AcademicSession::find()->select([
        //     'org_academic_session.acad_session_id',
        //     'org_academic_session.acad_session_name'
        // ])->distinct();

        $query->asArray();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'timetable_id' => $this->timetable_id,
            'prog_curriculum_course_id' => $this->prog_curriculum_course_id,
            'prog_curriculum_sem_group_id' => $this->prog_curriculum_sem_group_id,
            'exam_date' => $this->exam_date,
            'exam_venue' => $this->exam_venue,
            'exam_mode' => $this->exam_mode,
            'publish_status' => $this->publish_status,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['ilike', 'mrksheet_id', $this->mrksheet_id])
            ->andFilterWhere(['ilike', 'userid', $this->userid])
            ->andFilterWhere(['ilike', 'ip_address', $this->ip_address])
            ->andFilterWhere(['ilike', 'user_action', $this->user_action]);

        // dd($query->createCommand()->rawSql);

        return $dataProvider;
    }
}
