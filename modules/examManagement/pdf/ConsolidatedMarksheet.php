<?php

namespace app\modules\examManagement\pdf;

use app\models\OrgAcademicSession;
use app\models\OrgProgLevel;
use app\models\OrgProgrammeCurriculum;
use app\models\SmExamResult;
use Codedge\Fpdf\Fpdf\Fpdf;
use Yii;

class ConsolidatedMarksheet extends FPDF
{
    private $_data;
    private $_memberData;
    private $_arrData;
    private $_header;
    private $_headerSummary;
    private $_url;
    private $_phone;
    private $_email;
    private $_institution;
    private $_website;

    private $ydetails = [];

    public function __construct(array $data)
    {

        parent::__construct('L', 'mm', 'A4');

        $this->_headerSummary = [
            'Summary', 'Totals'
        ];
        $session = Yii::$app->session;
        $this->_phone = $session['orgDetails']['parent_institution_phone'];
        $this->_email = $session['orgDetails']['parent_institution_email'];
        $this->_website = $session['orgDetails']['parent_institution_website'];
        // $this->_institution = $session['orgDetails']['parent_institution_name'];
        $this->_institution = 'NATIONAL DEFENCE UNIVERSITY';
        //  $this->_url = Yii::getAlias('@app') . '/web/img/uon-logo.jpg';
        $this->_url = Yii::getAlias('@app') . $session['orgDetails']['parent_institution_logo'];
        //  $this->_url1 =$session['orgDetails']['parent_institution_logo'];
        $this->_header = [
            'a', 'd', 'k', 'v', 'f', 'w', 'c', 'g',
        ];

        $this->prepareData($data);
    }

    private function prepareData(array $pdfData)
    {
        ['requestData' => $requestData, 'data' => $data] = $pdfData;
        $this->_arrData = $data;

        $acad_year = OrgAcademicSession::findOne($requestData['acad_session_id'])->acad_session_name;
        $yos = OrgProgLevel::findOne($requestData['programme_level_id'])->programme_level_name;
        $programme = OrgProgrammeCurriculum::find()->select(['prog_curriculum_id', 'concat(prog_code, \' \',prog_short_name) as desc'])
            ->innerJoinWith('prog')
            ->where(['prog_curriculum_id' => $requestData['prog_curriculum_id']])
            ->asArray()->one();

        $this->ydetails = [
            'acad_year' => $acad_year,
            'yos' => $yos,
            'programme' => $programme['desc'],
            'level_of_study' => $requestData['programme_level_id'],
        ];
    }

    public function outputControl()
    {

        $this->fetchMMAcc();
        // $this->fetchMMAccSummary();
    }


    public function Header()
    {

        $this->SetFont('Helvetica', 'B', 13);
        $this->SetTextColor(0, 0, 0);
        $this->image($this->_url, 137.9, 10, 20, 20);
        $this->ln(23);

        $this->Cell(0, 0, $this->_institution, 0, 1, 'C');
        $this->SetFont('Helvetica', 'B', 10);
        $this->ln(-5.0);
        // $this->Cell(0, 21, 'Telephone:'.$this->_url1, 0, 0, 'L');
        $this->Cell(0, 21, 'Telephone:' . $this->_phone, 0, 0, 'L');
        $this->Cell(-273.5, 21, 'E-Mail: ' . $this->_email, 0, 0, 'C');
        //        $this->Cell(0, 21, 'Website: smis.uonbi.ac.ke', 0, 1, 'R');
        $this->Cell(0, 21, 'Website:' . $this->_website, 0, 1, 'R');
        $this->ln(-17.2);
        $this->Cell(0, 22, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _  _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _  ', 0, 1, 'C');
        $this->ln(-5.5);
    }


    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'R');
    }

    public function findRecommendation($stno, $level)
    {
        return SmExamResult::find()->where(['fk_registration_number' => $stno, 'level_of_study' => $level])->one();
    }

    public function fetchMMAcc()
    {
        $this->SetFont('Helvetica', 'B', 11);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 10, 'CONSOLIDATED MARKSHEET', 0, 1, 'C');
        $this->SetFont('Helvetica', 'B', 10);

        $this->ln(-10.5);
        $this->Cell(0, 23, date("D, d-F-Y h:i:s A"), 0, 1, 'C');
        $this->ln(-10);




        $acad_year = $this->ydetails['acad_year'];
        $yos = $this->ydetails['yos'];
        $programme = $this->ydetails['programme'];
        // $name = $this->_memberData->pluck('ALLNAMES')->first();

        $this->SetFont('Helvetica', 'B', 10);
        $this->SetTextColor(0, 0, 0);
        // $this->Cell(0, 42, 'Details:', 0, 1, 'L');
        // $this->ln(-37.5);
        $this->Cell(0, 43, "ACADEMIC YEAR: {$acad_year}, YEAR OF STUDY: {$yos}", 0, 1, 'L');
        $this->ln(-38.5);
        $this->Cell(0, 44, "PROGRAMME: {$programme}", 0, 1, 'L');
        $this->ln(-42);
        $this->SetFont('Helvetica', '', 10);


        $this->SetFont('Helvetica', 'B', 10);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 16, '_______________________________', 0, 1, 'R');
        $this->ln(-22.5);
        $this->Cell(0, 38, 'Dean/Director Signature', 0, 1, 'R');
        $this->ln(-16);
        $this->Cell(0, 2, 'Faculty/Institute/School Stamp', 0, 1, 'R');
        $this->Ln(15);



        // Colors, line width and bold font
        $this->SetFillColor(36, 33, 36);
        $this->SetTextColor(255);
        $this->SetDrawColor(36, 33, 36);
        $this->SetLineWidth(.3);


        $w = [];

        foreach (max($this->_arrData) as $key => $item) {
            if (str_starts_with($key, 'course_code')) {
                $w[] = 17;
            }
        }

        $k = array(142, 142);

        // Color and font restoration
        $this->SetFillColor(224, 224, 224);
        $this->SetTextColor(0);
        $this->SetFont('');

        $fill = false;
        $count = 0;
        foreach ($this->_arrData as $key => $row) {
            $recomm = $this->findRecommendation($row['student_number'], $this->ydetails['level_of_study'])->ext_result;
            $result = $this->findRecommendation($row['student_number'], $this->ydetails['level_of_study'])->result;
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell($k[0], 6, $key + 1 . '. ' . $row['student_number'] . ' ' . $row['name'], 1, 0, 'L', $fill);
            $this->Cell($k[1], 6, $result . ' : ' . $recomm, 1, 0, 'L', $fill);
            $this->Ln();
            $this->SetFont('');

            foreach ($row as $key => $value) {
                if (str_starts_with($key, 'course_code')) {
                    $this->Cell($w[0], 6, $value, 1, 0, 'L', $fill);
                    $count++;
                }
            }


            // $length = array_sum(array_slice($w, $count + 1, 14 - $count));
            // $this->Cell($length, 12, '', 'LTR', 0, 'L', $fill);
            $this->Cell(8, 6, '', 'LRT', 0, 'L', $fill);
            $this->Cell(10, 6, '', 'LRT', 0, 'L', $fill);
            $this->Cell(10, 6, '', 'LRT', 0, 'L', $fill);
            $this->Cell(18, 6, '', 'LRT', 0, 'L', $fill);

            $this->Ln();
            foreach ($row as $key => $value) {
                if (str_starts_with($key, 'final_mark')) {
                    $str = str_replace("final_mark", "", $key);
                    if (empty($str)) {
                        $grade = $row['grade'];
                        $final_mark = $row['final_mark'];
                    } else {
                        $str = str_replace("final_mark_", "", $key);
                        $grade = $row['grade_' . $str];
                        $final_mark = $row['final_mark_' . $str];
                    }
                    $this->Cell($w[0], 6, "{$final_mark} | {$grade}", 1, 0, 'L', $fill);
                }
            }
            // $this->Cell($length, 6, '', 'LBR', 0, 'L', $fill);
            $this->SetFont('Helvetica', 'B', 10);

            $this->Cell(8, 6, $row['total_subjects'], 'LRB', 0, 'L', $fill);
            $this->Cell(10, 6, $row['total_mark'], 'LRB', 0, 'L', $fill);
            $this->Cell(10, 6, number_format($row['average_mark'], 2, '.', ''), 'LRB', 0, 'L', $fill);
            $this->SetFont('');
            $this->Cell(18, 6, $this->findRecommendation($row['student_number'], $this->ydetails['level_of_study'])->result, 'LRB', 0, 'L', $fill);
            $this->Ln();
        }
    }
}
