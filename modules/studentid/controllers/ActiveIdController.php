<?php

namespace app\modules\studentid\controllers;

use app\modules\studentid\models\IdRequestStatus;
use app\modules\studentid\models\StudentId;
use app\modules\studentid\models\StudentIdRequest;
use kartik\mpdf\Pdf;
use yii\db\Exception;
use yii\web\Controller;

class ActiveIdController extends Controller
{
    /**
     * @param $id
     * @return false|string
     * @throws Exception
     */
    public function actionPrintId($id): bool|string
    {
        $model = StudentId::findOne($id);

        $requestStatus = IdRequestStatus::getStatusId(IdRequestStatus::CLOSED);
        $idRequest = StudentIdRequest::findOneByCurrProgId($model->student_prog_curr_id, $requestStatus[0]);

        $this->view->title = 'Print single ID card';
        return $this->render('print-single', [
            'model' => $model,
            'idRequest' => $idRequest
        ]);

    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionExportPdf($id): mixed
    {

        $model = StudentId::findOne($id);

        $requestStatus = IdRequestStatus::getStatusId(IdRequestStatus::CLOSED);
        $idRequest = StudentIdRequest::findOneByCurrProgId($model->student_prog_curr_id, $requestStatus[0]);

        $content = $this->renderPartial('print-single', [
            'model' => $model,
            'idRequest' => $idRequest
        ]);


        $pdf = new Pdf([
            'content' => $content,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'cssFile' => '@webroot/css/student-id.css',
            'methods' => [
                'SetTitle' => 'Export student id to PDF',
                'SetSubject' => 'Student id generated from system kindly print it at your convenience.',
                'SetHeader' => ['Student identification ||Generated On: ' . date("r")],
                'SetFooter' => ['|Page {PAGENO}|'],
                'SetAuthor' => 'masgeek',
                'SetCreator' => 'masgeek',
                'SetKeywords' => 'Export, PDF, MPDF, Outputf',
            ]
        ]);

        return $pdf->render();

    }

}
