<?php
/**
 * @author Githaiga Maina <githaigamaina@uonbi.ac.ke>
 * @date: 7/20/2023
 * @time: 05:59 AM
 */

namespace app\modules\examManagement\controllers;

use app\models\CrProgCurrTimetable;
use app\models\Employee;
use app\modules\examManagement\models\search\MarksSearch;
use app\modules\examManagement\models\search\ProgrammesSearch;
use app\modules\examManagement\models\search\TimetablesSearch;
use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRegistration\models\ProgCurrSemesterGroup;
use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\SPStudentCourse;
use app\modules\studentRegistration\models\StudentCourse;
use app\modules\studentRegistration\models\StudyGroup;
use app\modules\examManagement\models\search\ExamProcessingSearch;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

use function Sodium\increment;

class FinalExamProcessingController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    private $programmeCurriculum; #get the programme curriculum
    private $progCurrLevelRequirements; # programme curriculum level requirements
    private $progCurrGroupRequirements; # programme curriculum level group requirements
    private $studentsToProcess; #Students who satisfies processing parameters from form
    private $studentCourses; # all the students courses
    private $programCurriculumGradingSystem; # program curriculum grading system
    private $progCurrLevelGroupRequirements;
    private $programmeCurrGroupCourses;
    private $registrationNumber;
    private $studentResults = array(); // level results
    private $studentsLevelResults = array();
    private $traceProcessing = 0;


    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
          'access' => [
            'class' => AccessControl::class,
            'only' => ['logout'],
            'rules' => [
              [
                'actions' => ['logout'],
                'allow' => true,
                'roles' => ['@'],
              ],
            ],
          ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['error' => "string[]"])]
    public function actions(): array
    {
        return [
          'error' => [
            'class' => 'yii\web\ErrorAction',
          ]
        ];
    }

    /**
     * process exams
     */


    public function actionProcess()
    {

        $searchModel = new ExamProcessingSearch();

        if (!empty(Yii::$app->request->post())) {
            $params = Yii::$app->request->post();
            $filters = $params['ExamProcessingSearch'];
            $this->traceProcessing = $filters['trace_processing'] ?? 0;
            /**
             * get students to process
             */
            $this->studentsToProcess = $searchModel->getStudentsToProcess(Yii::$app->request->post());
            if (is_array($this->studentsToProcess) && count($this->studentsToProcess)) {
                echo "X = NOT Processed Because Courses Taken Less than Min. (" . $filters['min_courses_taken'] . ") or Greater than Max. (" . $filters['max_courses_taken'] . ")<br />";
                foreach ($this->studentsToProcess as $key => $student) {
                    # get programme currriculum
                    $this->programmeCurriculum = $searchModel->getProgrammeCurriculum(Yii::$app->request->post());
                    #get programme curriculum level requirements
                    $this->progCurrLevelRequirements = $searchModel->getProgCurrLevelReq(Yii::$app->request->post());
                    #  get programme curriculum grading system
                    $this->programCurriculumGradingSystem = $searchModel->getProgramCurriculumGradingSystem(Yii::$app->request->post());
                    #get programme curriculum level group requirements
                    $this->progCurrLevelGroupRequirements = $searchModel->getProgCurrLevelGroupRequirements(Yii::$app->request->post());
                    //  echo "Programme Curriculum Group Courses:<br />";
                    $this->programmeCurrGroupCourses = $searchModel->getProgCurrGroupCourses($this->programmeCurriculum['prog_curriculum_id']);
                    $post = Yii::$app->request->post();
                    $filters = $post['ExamProcessingSearch'];
                    $this->processRecommendation($student['registration_number'], $filters['min_courses_taken'], $filters['max_courses_taken']);
                }#end foreach
                echo $this->getProgCurriculumRequirements(); #print programme curriculum requirement
            }

            exit;
        } else {
            $dataProvider = new ActiveDataProvider([
              'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }
        // $searchModel->getStudentCourses();
        return $this->render('exam-processing-params', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
        ]);
    }#end function

    private function processRecommendation($registrationNumber, $minTotalCourses, $maxTotalCourses)
    {

        if (count($this->programmeCurriculum)) {
            $searchModel = new ExamProcessingSearch();
            $this->registrationNumber = $registrationNumber;

            #get the student's courses
            $this->studentCourses = $searchModel->getStudentCourses($this->registrationNumber);
            $coursesTaken = count($this->studentCourses) ?? 0;

            if ($coursesTaken >= $minTotalCourses && $coursesTaken <= $maxTotalCourses) {
                if (count($this->progCurrLevelRequirements)) {
                    #programme curriculum level requirements set
                    foreach ($this->progCurrLevelRequirements as $levelKey => $levelValue) {
                        $studentsLevelCourses = $this->getStudentLevelCourses($this->studentCourses, $levelValue['prog_study_level']);

                        $arrayGroupCoursesRequirements = $this->getProgCurriculumLevelGroupRequirements($levelValue['prog_curr_level_req_id'], $this->progCurrLevelGroupRequirements); #get courses by each group level

                        foreach ($arrayGroupCoursesRequirements as $k => $v_GroupCoursesRequirements) {
                            $prog_curr_group_requirement_id = $v_GroupCoursesRequirements['prog_curr_group_requirement_id'];
                            $groupCourses = $this->getProgCurriculumGroupCourses($prog_curr_group_requirement_id, $this->programmeCurrGroupCourses);
                            $this->studentResults[$this->registrationNumber][$levelValue['prog_study_level']][$v_GroupCoursesRequirements['course_group_name']] [] = $this->processGroupCourses($v_GroupCoursesRequirements, $groupCourses, $studentsLevelCourses, $levelValue);
                        } # ($arrayGroupCourses as $k => $v_GroupCourses)
                        $this->studentsLevelResults[$this->registrationNumber][$levelValue['prog_study_level']] = $this->processLevelRecommendation($this->studentResults[$this->registrationNumber][$levelValue['prog_study_level']], $levelValue);
                    } #end foreach

                    if (count($this->studentsLevelResults[$this->registrationNumber])) {
                        #get final recommendation
                        $this->getStudentLevelRecommedation($this->studentsLevelResults[$this->registrationNumber], $this->progCurrLevelRequirements);
                    }
                // echo "<p>Level Results:&nbsp;</p><pre>";
                //  print_r($this->studentsLevelResults);
                //  echo "</pre>";


                } else {
                    return array('error' => 'Programme Curriculum level requirements not set!');
                }
            } else {
                echo $this->registrationNumber . ": X <br/>";
            }
        } else {
            return array('error' => 'Programme Curriculum not found!');

        }#end if


    }#end function

    /**
     * @param $arrayLevelCourses
     * @param $arrayLevelRequirements
     * @return void
     */
    private function processLevelRecommendation($arrayLevelCourses, $arrayLevelRequirements)
    {

        $levelTotalMarks = 0;
        $levelGPATotalMarks = 0;
        $levelTotalCoursesTaken = 0;
        $levelGPACoursesTaken = 0;
        $levelTotalPassCourses = 0;
        $levelExemptCourses = 0;
        $levelGPAValue = 0;
        $groupExemptCourses = 0;
        $levelFailedCourses = 0;
        $levelStatus = '';
        $levelPassStatus = array();// FAIL, PASS
        $levelResult = array();
        $levelMissingCourses = array();
        $FailedCourses = array();
        $failRecommendation = array();
        $i = 0;
        // print_r($arrayLevelCourses);

        foreach ($arrayLevelCourses as $key => $value) {
            // print_r($value);
            if (count($value) > 0) {
                $levelGPATotalMarks += $value[$i]['gpaTotalMarks'] ?? 0;
                $levelGPACoursesTaken += $value[$i]['groupGPACourses'] ?? 0;
                //$levelPassedCourses += $value[$i]['groupPassedCourses'];
                $levelPassStatus[] = $value[$i]['groupPassStatus'] ?? 0;
                $levelFailedCourses += $value[$i]['groupFailedCourses'] ?? 0;
                $passed = $value[$i]['PASSED'] ?? null;
                $levelPassed = is_array($passed) ? implode(', ', array_map(function ($v, $k) {
                    return sprintf("%s:%s", $k, $v);
                }, $passed, array_keys($passed))) : null;
                $levelStatus .= $levelPassed ? $key . " Required : " . $value[$i]['group_min_to_pass'] . " , Passed: " . $value[$i]['groupPassedCourses'] . " (" . $levelPassed . ")\n" : null;
                $failed = $value[$i]['FAILED'] ?? null;
                $levelFailed = is_array($failed) ? implode(', ', array_map(function ($v, $k) {
                    return sprintf("%s:%s", $k, $v);
                }, $failed, array_keys($failed))) : null;
                $levelStatus .= $levelFailed ? $key . " Required : " . $value[$i]['group_min_to_pass'] . " , Failed: (" . $levelFailed . ")\n" : null;
                if ($levelFailed) {
                    $FailedCourses[$key] = $levelFailed;
                    $levelPassStatus[] = 'FALSE';
                    $passStatus = false;
                }
                $missing = $value[$i]['MISSING'] ?? null;
                $levelMissing = $missing ? implode(', ', array_map(function ($v, $k) {
                    return sprintf("%s:%s", $k, $v);
                }, $missing, array_keys($missing))) : null;
                $levelStatus .= $levelMissing ? $key . " Required : " . $value[$i]['group_min_to_pass'] . " , Missing: (" . $levelMissing . ")<br />" : null;
                if ($levelMissing) {
                    $levelMissingCourses[$key] = $levelMissing;
                    $levelPassStatus[] = 'FALSE';
                    $passStatus = false;
                }

            }
        }

        $result = (in_array('FALSE', $levelPassStatus)) ? $arrayLevelRequirements['fail_result'] : $arrayLevelRequirements['pass_result']; // get the level result

        $levelResult[$arrayLevelRequirements['prog_study_level']] = $levelStatus;
        /* echo "<br />";
         echo $levelGPACoursesTaken . " Courses taken <br />";
         echo $levelGPATotalMarks . " Total Marks <br />";
         echo $levelGPATotalMarks ?? $levelGPATotalMarks / $levelGPACoursesTaken . " Average";*/

        $missingRecommendation = $levelMissingCourses ?? null;
        $failRecommendation = $FailedCourses ?? null;

        // if ($levelGPACoursesTaken > 0) {
        $gpa = $levelGPATotalMarks / $levelGPACoursesTaken;
        //  }

        $levelResult[$arrayLevelRequirements['prog_study_level']] = array('levelofstudy' => $arrayLevelRequirements['prog_study_level'], 'result' => $result, 'fail_recommendation' => $failRecommendation, 'incomplete_recommendation' => $missingRecommendation, 'no_of_courses_taken' => $levelGPACoursesTaken, 'total_marks' => $levelGPATotalMarks, 'gpa' => $gpa, 'leveltrail' => $levelStatus, 'gpa_weight' => $arrayLevelRequirements['gpa_weight'] ?? 0);
        //  print_r($levelResult);
        return $levelResult ?? null;
    }

    /**
     * function to process Group Courses
     * @param $groupCoursesRequirements
     * @param $groupCourses
     * @param $studentlevelCourses
     * @return array
     */
    private function processGroupCourses($groupCoursesRequirements, $groupCourses, $studentlevelCourses): array
    {
        $arrayResult = array();#keep and return results
        $groupPassedCourses = 0;
        $groupFailedCourses = 0;
        $groupMissingCourses = 0;
        $groupGPACourses = 0;
        $groupTotalMarks = 0;
        $groupPassStatus = 'FALSE';
        $groupCoursesTaken = 0;

        $processedGroupCourses = array();
        if (count($groupCoursesRequirements) && count($groupCourses)) {

            switch (strtoupper(trim($groupCoursesRequirements['course_group_type']))) { #process all courses
                case 'COMPULSORY':
                    $gpaCourses = 0;
                    $groupTotalMarks = 0;
                    foreach ($groupCourses as $key => $value) {
                        foreach ($studentlevelCourses as $k => $v) {
                            if ($value['course_id'] == $v['course_id']) {
                                #set the GPA courses
                                $groupGPACourses += $value['credit_factor'];
                                $groupTotalMarks += $v['final_mark'] * $value['credit_factor'];
                                $processedGroupCourses[] = $value;
                                #course taken but failed
                                if ($v['final_mark'] && $v['final_mark'] >= $this->programmeCurriculum['pass_mark']) {
                                    #final mark is not null and is less than passmark
                                    $arrayResult ['PASSED'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                    $groupPassedCourses++;
                                    $groupCoursesTaken++;
                                }
                                if (!$v['final_mark']) {
                                    #registered but not marks missing
                                    $arrayResult['MISSING'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                    $groupMissingCourses++;
                                }
                                if ($v['final_mark'] && $v['final_mark'] < $this->programmeCurriculum['pass_mark']) {
                                    #final mark is not null and is greater or equal to passmark
                                    $arrayResult ['FAILED'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                    $groupFailedCourses++;
                                    $groupCoursesTaken++;
                                }#end if
                            } #end if
                        } #end foreach $studentlevelCourses
                    }#end for each $groupCourses
                    $missingGroupCourses = $this->array_difference($groupCourses, $processedGroupCourses);
                    if (is_array($missingGroupCourses) && count($missingGroupCourses)) {
                        foreach ($missingGroupCourses as $key => $value) {
                            $arrayResult['MISSING'][$value['course_code'] . '-' . $value['course_id']] = null;
                            $groupMissingCourses++;
                        }
                    }
                    if (($groupMissingCourses > 0 && $groupFailedCourses > 0) || $groupPassedCourses < $groupCoursesRequirements['min_group_courses']) {
                        $groupPassStatus = 'FALSE';
                    } else {
                        $groupPassStatus = 'TRUE';
                    }
                    break;
                case 'OPTIONAL':
                    #if pass type is BEST
                    if (strtoupper(trim($groupCoursesRequirements['extra_courses_processing'])) == 'ALL' or ($groupCoursesRequirements['group_pass_type'] == 'BEST' || $groupCoursesRequirements['group_pass_type'] == 'ALL')) {#what to do with extra course
                        $gpaCourses = 0; #initilize to 0
                        #extra courses affects the GPA
                        foreach ($groupCourses as $key => $value) {
                            foreach ($studentlevelCourses as $k => $v) {
                                if ($value['course_id'] == $v['course_id']) {
                                    $groupGPACourses += $value['credit_factor'];
                                    $groupTotalMarks += $v['final_mark'] * $value['credit_factor'];
                                    #course taken and passed
                                    if ($v['final_mark'] && $v['final_mark'] >= $this->programmeCurriculum['pass_mark']) {
                                        #final mark is not null and is greater than or equal to passmark, pass
                                        $arrayResult ['PASSED'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                        $groupPassedCourses++;
                                        $groupCoursesTaken++;
                                    } elseif ($v['final_mark'] == null) {
                                        #registered but no marks missing
                                        $arrayResult['MISSING'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                        $groupMissingCourses++;
                                    } elseif ($v['final_mark'] && $v['final_mark'] < $this->programmeCurriculum['pass_mark']) {
                                        #final mark is not null and is greater or equal to passmark
                                        $arrayResult ['FAILED'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                        $groupFailedCourses++;
                                        $groupCoursesTaken++;
                                    }#end if
                                } else {
                                    //$arrayResult['MISSING'][$value['course_code']] = 2;
                                    $passed = false;
                                }
                            } #end foreach $studentlevelCourses
                        }#end for each $groupCourses
                        if (($groupCoursesTaken >= $groupCoursesRequirements['min_group_courses']) && $groupPassedCourses >= $groupCoursesRequirements['min_group_pass']) {
                            $groupPassStatus = 'TRUE';
                        } else {
                            $groupPassStatus = 'FALSE';
                        }#end if
                    } else {
                        #ignore extra courses
                        $optionalCoursesPassed = 0; #initilize courses passed to 0
                        foreach ($studentlevelCourses as $k => $v) {
                            foreach ($groupCourses as $key => $value) {
                                if ($value['course_id'] == $v['course_id']) {
                                    #course taken and passed
                                    if ($v['final_mark'] && $v['final_mark'] >= $this->programmeCurriculum['pass_mark']) {
                                        #final mark is not null and is greater than or equal to passmark, pass
                                        if ($optionalCoursesPassed < $groupCoursesRequirements['min_group_pass']) {
                                            $groupGPACourses += $value['credit_factor'];
                                            $groupTotalMarks += $v['final_mark'] * $value['credit_factor'];
                                        }
                                        $arrayResult['PASSED'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                        $optionalCoursesPassed++; #increment courses optional passed
                                    } elseif (($v['final_mark'] == null) && ($optionalCoursesPassed < $groupCoursesRequirements['min_group_pass'])) {
                                        #registered but no marks missing and number of passed courses is less than min_group_pass
                                        $arrayResult['MISSING'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                    } elseif (($v['final_mark'] && $v['final_mark'] < $this->programmeCurriculum['pass_mark']) && ($optionalCoursesPassed < $groupCoursesRequirements['min_group_pass'])) {
                                        #final mark is  null and number of passed courses is less than min_group_pass
                                        $arrayResult['FAILED'][$value['course_code'] . '-' . $v['course_id']] = $v['final_mark'];
                                    }#end if
                                }
                            }#end for each $groupCourses
                        } #end foreach $studentlevelCourses
                        if (($groupCoursesTaken >= $groupCoursesRequirements['min_group_courses']) && $groupPassedCourses >= 'min_group_pass') {
                            $groupPassStatus = 'TRUE';
                        } else {
                            $groupPassStatus = 'FALSE';
                        }#end if
                    }#
                    break;
                default:
                    break;
            }#end switch
            $arrayResult['group_min_to_pass'] = $groupCoursesRequirements['min_group_pass'];
            $arrayResult['groupPassedCourses'] = $groupPassedCourses;
            $arrayResult['groupMissingCourses'] = $groupMissingCourses;
            $arrayResult['groupFailedCourses'] = $groupFailedCourses;
            $arrayResult['groupGPACourses'] = $groupGPACourses;
            $arrayResult['gpaTotalMarks'] = $groupTotalMarks;
            $arrayResult['groupCoursesTaken'] = $groupCoursesTaken;
            $arrayResult['groupPassStatus'] = $groupPassStatus;
        }#end if
        return $arrayResult;
    }#end function

    /**
     * @return string|null
     * get curriculum required courses for screen printing.
     */
    private function getProgCurriculumRequirements()
    {
        $programmeCurriculum = null;
        if (count($this->programmeCurriculum)) {
            #programme curriculum exits
            if (count($this->progCurrLevelRequirements)) {
                #programme curriculum level requirements set
                foreach ($this->progCurrLevelRequirements as $levelKey => $levelValue) {
                    $programmeCurriculum .= '<strong>' . $this->programmeCurriculum['prog_short_name'] . '(' . $this->programmeCurriculum['prog_curriculum_desc'] . ') Level ' . $levelValue['prog_study_level'] . '</strong></strong><br />';
                    $programmeCurriculum .= "Minimum courses : " . $levelValue['min_courses_taken'] . " Minimum to pass :" . $levelValue['min_pass_courses'] . " GPA courses : " . $levelValue['gpa_courses'] . " GPA weight :" . $levelValue['gpa_weight'] . "<br />";
                    $arrayGroupCourses = $this->getProgCurriculumLevelGroupRequirements($levelValue['prog_curr_level_req_id'], $this->progCurrLevelGroupRequirements);
                    foreach ($arrayGroupCourses as $k => $v_GroupCourses) {
                        $prog_curr_group_requirement_id = $v_GroupCourses['prog_curr_group_requirement_id'];
                        $groupCourses = $this->getProgCurriculumGroupCourses($prog_curr_group_requirement_id, $this->programmeCurrGroupCourses);
                        $programmeCurriculum .= "$v_GroupCourses[course_group_name]($v_GroupCourses[course_group_type]) <br />";
                        $i = 0;
                        foreach ($groupCourses as $k => $v) {
                            $i++;
                            $programmeCurriculum .= $i . ". $v[course_code]  x $v[credit_factor] <br />";//- $v[course_name]
                        }#end foreach
                    } # ($arrayGroupCourses as $k => $v_GroupCourses)
                } #end foreach
            } else {
                return $programmeCurriculum ?? 'Programme Curriculum level requirements not set!';
            }
        } else {
            return $programmeCurriculum ?? 'Programme Curriculum not found!';
        }#end if
        return $programmeCurriculum ?? null;
    }#end function

    /**
     * @param $array1
     * @param $array2
     * @return array
     */
    private function array_difference($array1, $array2): array
    {

        $result = array_map('unserialize', array_diff(array_map('serialize', $array1), array_map('serialize', $array2)));
        return $result;
    }

    /**
     * @return void
     * get student recommendation
     */
    private function getStudentLevelRecommedation($studentResults, $progCurrLevelRequirements)
    {
        $searchModel = new ExamProcessingSearch();
        $fk_registration_number = '';
        $result = '';
        $ext_result = '';
        $level_of_study = 0;
        $total_marks = 0.00;
        $courses_taken = 0;
        $gpa_courses = 0;
        $levelTrail = '';
        $gpa = 0.0000;
        $levelPassStatus = true;

        $arrayFailedCourses = array();
        $arrayMissingCourses = array();
        $grading_system_id = $this->programmeCurriculum['grading_system_id'];
        $award_rounding = (strtoupper(trim($this->programmeCurriculum['award_rounding'])) == 'ROUND') ? 'ROUND' : 'TRUNCATE';
        $average_type = strtoupper(trim($this->programmeCurriculum['average_type'])) ?? null;# weighted
        $finalGPA = 0;
        $finalGPAWeight = 0;
        if (is_array($studentResults) && is_array($progCurrLevelRequirements)) {
            foreach ($studentResults as $key => $value) {
                foreach ($progCurrLevelRequirements as $k => $v) {
                    if ($v['prog_study_level'] === $key) {
                        if (count($value[$key]['fail_recommendation']) > 0) {
                            array_push($arrayFailedCourses, $value[$key]['fail_recommendation']);
                        }
                        if (count($value[$key]['incomplete_recommendation']) > 0) {
                            array_push($arrayMissingCourses, $value[$key]['incomplete_recommendation']);
                        }
                        if (count($arrayFailedCourses) && count($arrayMissingCourses) > 0) {
                            $ext_result = $v['fail_recommendation'] . "(" . $this->array_to_string($arrayFailedCourses) . ") " . $v['incomplete_recommendation'] . "(" . $this->array_to_string($arrayMissingCourses) . ")";
                            $result = $v['fail_result'];
                            $levelPassStatus = false;
                        } elseif (count($arrayFailedCourses)) {
                            $ext_result = $v['fail_recommendation'] . "(" . $this->array_to_string($arrayFailedCourses) . ")";
                            $result = $v['fail_result'];
                            $levelPassStatus = false;
                        } elseif (count($arrayMissingCourses)) {
                            $ext_result = $v['incomplete_recommendation'] . "(" . $this->array_to_string($arrayMissingCourses) . ")";
                            $result = $v['incomplete_result'];
                            $levelPassStatus = false;
                        } else {
                            $ext_result = $v['pass_recommendation'];
                            $result = $v['pass_result'];
                        }
                        if ($average_type == 'WEIGHTED') {
                            if ($value[$key]['gpa_weight'] > 0) {
                                $finalGPA += $value[$key]['gpa'] * $value[$key]['gpa_weight'];
                                $finalGPAWeight += $value[$key]['gpa_weight'];
                            } else {
                                //  $finalGPA += $value[$key]['gpa'];
                                // $finalGPAWeight++;
                            }
                        } else {
                            #do an average
                            $finalGPA += $value[$key]['gpa'];
                            $finalGPAWeight++;
                        }#end if $average_type == 'WEIGHTED' && $value['gpa_weight']
                        $fk_registration_number = $this->registrationNumber ?? null;
                        $levelTrail = $value[$key]['leveltrail'];
                        // $result = $value[$key]['result'];
                        $level_of_study = $key;
                        // print_r($this->programmeCurriculum);
                        if ($key == $this->programmeCurriculum['duration'] && $levelPassStatus == true) {
                            $gpa = $finalGPA / $finalGPAWeight;
                            $gpa = ($award_rounding == 'ROUND') ? round($gpa, 0) : floor($gpa);
                            $recommendation = (trim($this->getSingleRecommedation($gpa))) ? ' at ' . $this->getSingleRecommedation($gpa) : null;
                            $ext_result = $ext_result . " " . $this->programmeCurriculum['prog_curriculum_desc'] . $recommendation;
                        } else {
                            //$ext_result = $ext_result;
                            $gpa = $value[$key]['gpa'];
                        }
                        $total_marks = $value[$key]['total_marks'];
                        $courses_taken = $value[$key]['no_of_courses_taken'];
                        $gpa_courses = 0;
                        $studentRecomendation[$key]['fk_registration_number'] = $fk_registration_number;
                        $studentRecomendation[$key]['levelTrail'] = $levelTrail;
                        $studentRecomendation[$key]['result'] = $result;
                        $studentRecomendation[$key]['ext_result'] = $ext_result;
                        $studentRecomendation[$key]['total_marks'] = $total_marks;
                        $studentRecomendation[$key]['courses_taken'] = $courses_taken;
                        $studentRecomendation[$key]['gpa_courses'] = $gpa_courses;
                        $studentRecomendation[$key]['gpa'] = $gpa;
                        $studentRecomendation[$key]['level_of_study'] = $level_of_study;

                        if ($this->traceProcessing == 1) {
                            echo "<br />";
                            echo $fk_registration_number . " Level of study : $level_of_study, Courses taken: $courses_taken, Average:  $gpa<br />";
                            echo "Trail :$levelTrail <br />";
                            echo "Result:" . $result . " $ext_result<br />";
                        }
                        $searchModel->postStudentRecommendation($studentRecomendation);
                    }
                }#end for each
            }#end foreach
        }#end if
    }#end function

    private function array_to_string($array)
    {
        $ar = array();
        $str = null;
        if (count($array)) {
            $arrayKey = array();
            $i = 0;
            foreach ($array as $k => $v) {
                foreach ($v as $key => $value) {
                    $arrayKey[$key][$i] = $value;
                }
                $i++;
            }
            foreach ($arrayKey as $key => $value) {
                $str .= count($value) ? "$key (" . implode(', ', array_map(function ($v, $k) {
                    return sprintf("%s", $v);
                }, $value, array_keys($value))) . ") " : null;


            }
            return $str ?? null;
        }
    }

    private function getStudentLevelCourses($studentCourses, $level)
    {
        if (count($studentCourses) && isset($level)) {
            $studentLevelCourses = array();
            foreach ($studentCourses as $key => $value) {
                if ($value['level_of_study'] == $level) {
                    $studentLevelCourses[] = $value;
                }
            }#end for each
            return $studentLevelCourses ?? null;
        }
    }# end function

    private function getSingleRecommedation($mark)
    {
        if ($mark > 0 && is_array($this->programCurriculumGradingSystem)) {
            foreach ($this->programCurriculumGradingSystem as $key => $value) {
                if (($mark >= $value['lower_bound']) && ($mark <= $value['upper_bound'])) {
                    $singleGradeRecommendation = $value['extlegend'];
                } // if (($examMarks >= $lowerBound ) &&
            }
        }
        return $singleGradeRecommendation ?? null;
    }

    /**
     * @param $prog_curr_level_req_id
     * @param $arrayGroupsReq
     * @return array|null
     */
    private function getProgCurriculumLevelGroupRequirements($prog_curr_level_req_id, $arrayGroupsReq)
    {
        if (is_numeric($prog_curr_level_req_id) && count($arrayGroupsReq)) {
            $groupRequirements = array();
            foreach ($arrayGroupsReq as $key => $value):
                if ($value['prog_curr_level_req_id'] == $prog_curr_level_req_id) {
                    $groupRequirements[] = $value;
                }
            endforeach;
            return $groupRequirements ?? null;
        }
        return null;
    }#end function

    /**
     * @param $prog_curr_group_requirement_id
     * @param $arrayGroupCourses
     * @return array|null
     */
    private function getProgCurriculumGroupCourses($prog_curr_group_requirement_id, $arrayGroupCourses)
    {
        if (is_numeric($prog_curr_group_requirement_id) && is_array($arrayGroupCourses)) {
            $groupCourses = array();
            foreach ($arrayGroupCourses as $key => $value):
                if ($value['prog_curr_group_requirement_id'] == $prog_curr_group_requirement_id) {
                    $groupCourses[] = $value;
                }
            endforeach;
            return $groupCourses ?? null;
        }
        return null;
    }#end function
}#end class
