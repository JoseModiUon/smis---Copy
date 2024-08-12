<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/16/2023
 * @time: 4:42 PM
 */
declare(strict_types=1);

namespace app\modules\courseRegistration\models\search;

use app\modules\studentRegistration\models\AcademicSessionSemester;
use app\modules\studentRegistration\models\ProgrammeCurriculumTimetable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

final class TimetablesSearch extends ProgrammeCurriculumTimetable
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'cse.course_code',
            'cse.course_name'
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
                    'cse.course_code',
                    'cse.course_name'
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
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $year = $params['year'];
        $progCode = $params['progCode'];
        $level = $params['level'];
        $semesterId = $params['semesterId'];
        $groupId = $params['groupId'];
        $semesterType = $params['semesterType'];

        $partialMarksheetId = $year . '_';

        if (empty($progCode)) {
            $partialMarksheetId .= 'PROG' . '_';
        } else {
            $partialMarksheetId .= $progCode . '_';
        }

        if (empty($level)) {
            $partialMarksheetId .= 'LVL' . '_';
        } else {
            $partialMarksheetId .= $level . '_';
        }

        if (empty($semesterId)) {
            $partialMarksheetId .= 'SEM' . '_';
        } else {
            $semGroup = (new Query())
                ->select('sc.semester_code')
                ->from(SMIS_DB_SCHEMA . '.org_prog_curr_semester_group pcsg')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_prog_curr_semester pcs', 'pcs.prog_curriculum_semester_id=pcsg.prog_curriculum_semester_id')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session_semester ass', 'ass.acad_session_semester_id=pcs.acad_session_semester_id')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_semester_code sc', 'sc.semester_code=ass.semester_code')
                ->where(['pcsg.prog_curriculum_sem_group_id' => $semesterId])
                ->one();

            $partialMarksheetId .= $semGroup['semester_code'] . '_';
        }

        if (empty($groupId)) {
            $partialMarksheetId .= 'GRP';
        } else {
            $partialMarksheetId .= $groupId;
        }

        // Get courses in the matched timetable
        $query = ProgrammeCurriculumTimetable::find()->alias('tt')
            ->select([
                'tt.timetable_id',
                'tt.exam_date',
                'tt.exam_venue',
                'tt.exam_mode',
                'tt.prog_curriculum_course_id',
                'tt.mrksheet_id',
                'tt.prog_curriculum_sem_group_id'
            ])
            ->where(['like', 'mrksheet_id', $partialMarksheetId . '%', false])
            ->joinWith(['examMode em' => function (ActiveQuery $q) {
                $q->select([
                    'em.exam_mode_id',
                    'em.exam_mode_name'
                ]);
            }], true, 'INNER JOIN')
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
            ->joinWith(['programmeCurriculumSemesterGroup pcsg' => function (ActiveQuery $q) {
                $q->select([
                    'pcsg.prog_curriculum_sem_group_id',
                    'pcsg.prog_curriculum_semester_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumSemesterGroup.progCurrSemester pcs' => function (ActiveQuery $q) {
                $q->select([
                    'pcs.prog_curriculum_semester_id',
                    'pcs.semester_type_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumSemesterGroup.progCurrSemester.semesterType st' => function (ActiveQuery $q) {
                $q->select([
                    'st.semester_type_id',
                    'st.semester_type'
                ]);
            }], true, 'INNER JOIN')
            ->andWhere(['st.semester_type' => $semesterType])
            ->asArray();

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

        $query->andFilterWhere(['like', 'cse.course_code', $this->getAttribute('cse.course_code')]);
        $query->andFilterWhere(['like', 'cse.course_name', $this->getAttribute('cse.course_name')]);

        return $dataProvider;
    }
}