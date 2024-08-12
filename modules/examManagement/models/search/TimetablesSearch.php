<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 11:00 AM
 */

namespace app\modules\examManagement\models\search;

use app\modules\studentRegistration\models\ProgrammeCurriculumTimetable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class TimetablesSearch extends ProgrammeCurriculumTimetable
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'programmeCurriculumCourse.course.course_code',
            'programmeCurriculumCourse.course.course_name'
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
                    'programmeCurriculumCourse.course.course_code',
                    'programmeCurriculumCourse.course.course_name'
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
        $marksheetId = $moreParams['marksheetId'];
        $semesterCode = $moreParams['semesterCode'];
        $groupId = $moreParams['groupId'];

        $query = ProgrammeCurriculumTimetable::find()->alias('pct')
            ->select([
                'pct.timetable_id',
                'pct.mrksheet_id',
                'pct.prog_curriculum_course_id',
                'pct.prog_curriculum_sem_group_id'
            ])
            ->where(['like', 'pct.mrksheet_id', $marksheetId . '%', false])
            ->joinWith(['programmeCurriculumCourse pcc' => function (ActiveQuery $q) {
                $q->select([
                    'pcc.prog_curriculum_course_id',
                    'pcc.course_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumCourse.course cse' => function (ActiveQuery $q) {
                $q->select([
                    'cse.course_id',
                    'cse.course_code',
                    'cse.course_name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumSemesterGroup pcsg' => function(ActiveQuery $q){
                $q->select([
                    'pcsg.prog_curriculum_sem_group_id',
                    'pcsg.prog_curriculum_semester_id',
                    'pcsg.study_centre_group_id'
                ]);
            }], true, 'INNER JOIN');

            if(!empty($semesterCode)){
                $query->joinWith(['programmeCurriculumSemesterGroup.progCurrSemester pcs' => function(ActiveQuery $q){
                    $q->select([
                        'pcs.prog_curriculum_semester_id',
                        'pcs.acad_session_semester_id'
                    ]);
                }], true, 'INNER JOIN')
                ->joinWith(['programmeCurriculumSemesterGroup.progCurrSemester.academicSessionSemester ass' => function(ActiveQuery $q){
                    $q->select([
                        'ass.acad_session_semester_id',
                        'ass.semester_code'
                    ]);
                }], true, 'INNER JOIN')
                ->andWhere(['ass.semester_code' => $semesterCode]);
            }

            if(!empty($groupId)){
                $query->joinWith(['programmeCurriculumSemesterGroup.studyCentreGroup scg' => function(ActiveQuery $q){
                    $q->select([
                        'scg.study_centre_group_id',
                        'scg.study_group_id'
                    ]);
                }], true, 'INNER JOIN')
                ->andWhere(['scg.study_group_id' => $groupId]);
            }

            $query->asArray();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => false,
                'pagination' => [
                    'pagesize' => 50,
                ],
            ]);

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'cse.course_code', $this->getAttribute('programmeCurriculumCourse.course.course_code')]);
        $query->andFilterWhere(['like', 'cse.course_name', $this->getAttribute('programmeCurriculumCourse.course.course_name')]);

        return $dataProvider;
    }
}
