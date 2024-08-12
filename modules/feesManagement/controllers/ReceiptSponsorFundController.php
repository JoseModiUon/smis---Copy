<?php

namespace app\modules\feesManagement\controllers;

use app\models\SmAcademicProgress;
use Yii;
use Error;
use yii\web\Controller;
use app\models\SmStudent;
use yii\filters\VerbFilter;
use app\models\SmStudentSponsor;
use app\models\Student;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\modules\feesManagement\traits\FeeTrait;
use app\modules\feesManagement\models\BankAccounts;
use app\modules\feesManagement\models\BankingSlips;
use app\modules\feesManagement\models\FeePayments;
use app\modules\feesManagement\models\FeeTransactions;
use app\modules\feesManagement\models\ReceiptSponsorFund;
use app\modules\feesManagement\models\SponsorBeneficiary;
use app\modules\feesManagement\models\search\ReceiptSponsorFundSearch;
use app\modules\feesManagement\models\search\SponsorBeneficiarySearch;
use kartik\growl\Growl;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * ReceiptSponsorFundController implements the CRUD actions for ReceiptSponsorFund model.
 */
class ReceiptSponsorFundController extends Controller
{
    use FeeTrait;
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'find-branch') {
            $this->enableCsrfValidation = false;
        }
        if ($action->id == 'post-fee-payments') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ReceiptSponsorFund models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (array_key_exists('sponsor_id', $this->request->queryParams)) {
            Yii::$app->session->set('sponsor_id', $this->request->queryParams['sponsor_id']);
        }
        $model = new ReceiptSponsorFund;
        $searchModel = new ReceiptSponsorFundSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Returns Blank Excel file with fields for input
     *
     * @return Response
     */
    public function actionGetExcelFile()
    {
        $mySpreadsheet = new Spreadsheet();

        // delete the default active sheet
        $mySpreadsheet->removeSheetByIndex(0);

        // Create "Sheet 1" tab as the first worksheet.
        // https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/adding-a-new-worksheet
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "Sheet 1");
        $mySpreadsheet->addSheet($worksheet, 0);


        // sheet 1 contains the birthdays of famous people.
        $sheetData = ['reg_number', 'deposit_amount'];



        $worksheet->fromArray($sheetData);
        $worksheets = [$worksheet];

        foreach ($worksheets as $worksheet) {
            foreach ($worksheet->getColumnIterator() as $column) {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        try {
            // Save to file.
            $writer = new Xlsx($mySpreadsheet);
            ob_start();
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            $out = base64_encode($excelOutput);

            return $this->asJson(['success' => true, 'data' => $out]);
        } catch (\Exception $e) {
            return $this->asJson([
                'success' => false, 'message' => 'could not generate file.'
            ]);
        }
    }

    /**
     * Process Excel file after upload
     *
     * @return Response
     */
    public function actionUploadExcelFile()
    {
        $model = $this->getModel();

        try {

            $model->file = UploadedFile::getInstanceByName('file');

            $model->upload();
            $dest = Yii::getAlias('@app') . '/temp/xlsx/' . $model->file->baseName . '.' . $model->file->extension;

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($dest);
            $worksheet = $spreadsheet->getActiveSheet();
            $records = array_slice($worksheet->toArray(), 1);
            $reg_nos = array_map(fn ($array) => $array[0], $records);
            $post = $this->request->post('SponsorBeneficiarySearch');
            $receipt_id = $post['receipt_sponsor_fund_id'];

            $ben = SponsorBeneficiary::find()
                ->select(['sponsor_beneficiary_id', 'reg_no'])
                ->where([
                    'receipt_sponsor_fund_id' => $receipt_id,
                    // 'in', 'reg_no', $reg_nos
                ])->asArray()->all();


            $beneficiaryNos = [];
            // no beneficiaries have been created for the fund
            if (empty($ben)) {
                $beneficiaries = $this->genereteBeneficiary($records, $post);
                $beneficiaryNos = array_column($beneficiaries, 'sponsor_beneficiary_id');
            } else {
                // find if there already records that are beneficiaries of the fund
                //remove from
                $existingRegNos = [];
                $newRecs = [];
                foreach ($ben as $b) {
                    if (in_array($b['reg_no'], $reg_nos)) {
                        $beneficiaryNos[] = $b['sponsor_beneficiary_id'];
                        $existingRegNos[] = $b['reg_no'];
                    }
                }

                foreach ($records as $rec) {
                    if (!in_array($rec[0], $existingRegNos)) {
                        $newRecs[] = $rec;
                    }
                }
                $beneficiaries = $this->genereteBeneficiary($newRecs, $post);
                $beneficiaryNos = array_merge($beneficiaryNos, array_column($beneficiaries, 'sponsor_beneficiary_id'));
            }


            $collect = [
                'selectedBeneficiaries' => implode(',', $beneficiaryNos),
                'SponsorBeneficiarySearch' => $post
            ];

            unlink($dest);
            return $this->asJson(['success' => true, 'data' => $collect]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($model->getErrors());
        }
    }


    /**
     * Calls SponsorBeneficiary controller to create Beneficiaries
     *
     * @param array $records
     * @param array $post
     * @return array
     */
    private function genereteBeneficiary(array $records, array $post): array
    {
        $modelBeneficiaries = Yii::$app->runAction('fees-management/sponsor-beneficiary/bulk-creator', ['records' => $records, 'post' => $post]);
        return $modelBeneficiaries;
    }

    /**
     * Process excel file upload
     *
     * @return yii/base/Model
     */
    private function getModel()
    {
        return new class() extends yii\base\Model
        {
            /**
             * @var UploadedFile
             */
            public $file;

            public function rules()
            {
                return [
                    [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx'],
                ];
            }

            public function formName()
            {
                return 'UploadForm';
            }

            public function upload()
            {
                if ($this->validate()) {

                    $folder = Yii::getAlias('@app') . '/temp/xlsx';
                    if (!is_dir($folder)) {
                        FileHelper::createDirectory($folder, $mode = 0775, $recursive = true);
                    }
                    $this->file->saveAs($folder . '/' . $this->file->baseName . '.' . $this->file->extension);
                    return true;
                } else {
                    return false;
                }
            }
        };
    }

    /**
     * Displays a single ReceiptSponsorFund model.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($receipt_sponsor_fund_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($receipt_sponsor_fund_id),
        ]);
    }


    /**
     * Displays all Beneficiaries in a fund ReceiptSponsorFund model.
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionListBeneficiaryView()
    {
        $searchModel = new SponsorBeneficiarySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $sourceRef = Yii::$app->request->get();


        return $this->render('list-beneficiary-view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // 'sourceRef' => $sourceRef['source_reference']
        ]);
    }



    /**
     * Creates a new ReceiptSponsorFund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ReceiptSponsorFund();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'sponsor_id' => Yii::$app->session->get('sponsor_id')]);
            }
            dd($model->getErrors());
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * populates BankingSlips columns
     *
     * @param array $post
     * @return array
     */
    private function generateColumns(array $post): array
    {
        $selectedBeneficiaries = $post['selectedBeneficiaries'];
        $bankingDetails = $post['SponsorBeneficiarySearch'];

        $beneficiary = [];
        if (str_contains($selectedBeneficiaries, ',')) {
            $beneficiary = explode(',', $selectedBeneficiaries);
        } else {
            $beneficiary[] = $selectedBeneficiaries;
        }

        if (is_string($bankingDetails)) {
            $bankingDetails = json_decode($bankingDetails, true);
        }

        $columns = [];
        foreach ($beneficiary as $bn) {
            $column = [];
            $bnInstance = SponsorBeneficiary::findOne($bn);
            $receipt_sponsor = ReceiptSponsorFund::findOne($bnInstance->receipt_sponsor_fund_id);
            $sponsor = SmStudentSponsor::findOne($receipt_sponsor->sponsor_id);
            $bankAccounts = BankAccounts::findOne($bankingDetails['bank_account']);

            $bs = BankingSlips::find()->where([
                'sponsor_beneficiary_id' => $bnInstance->sponsor_beneficiary_id,

            ])->one();


            if ($bs) continue;
            $res = Yii::$app->getDb()->createCommand("select nextval('smis.banking_slips_receipt_no_seq') as receipt_no")->queryOne();

            $column = [
                'deposit_date' => $bnInstance->post_date,
                'deposit_type' => $bankingDetails['payment_type_id'],
                'deposit_amount' => $bnInstance->amount,
                'reg_number' => $bnInstance->reg_no,
                'other_names' => $bnInstance->name,
                'post_status' => 'NOT POSTED',
                // $column['post_comment'] = $bankingDetails['narration'];
                'account_no' => $bankAccounts->account_no,
                'receipt_no' => $res['receipt_no'],
                'process_date' => date('Y-m-d'),
                'batch_no' => (string) $bnInstance->sponsor_beneficiary_id,
                'pay_mode' => $bankingDetails['payment_mode'],
                // $columns['chq_no'] =    $columns['batch_no'] = $bnInstance->sponsor_beneficiary_id;
                'drawer_account' => $sponsor->sponsor_code,
                // $columns['trans_reference'] = $sponsor->sponsor_code;
                'branch_code' => $bankingDetails['branch'],
                'run_balance' => $bnInstance->getRunningBalance(),
                'last_update' => date('Y-m-d'),
                'user_id' => Yii::$app->user->identity->username,
                'drawer_name' => $sponsor->sponsor_name,
                'student_type' => $this->findCategory($bnInstance->reg_no),
                'source_reference' => $receipt_sponsor->source_reference,
                'sponsor_beneficiary_id' => $bnInstance->sponsor_beneficiary_id,
                'registration_number' => $bnInstance->reg_no,
                'value_date' => date('Y-m-d'),
                'file_id' => $bnInstance->file_path,
                'bank_id' => $bankAccounts->getBank()->one()->brank_id,
                'bank_number' => $bankAccounts->getBank()->one()->bank_code
            ];
            $columns[] = $column;
        }
        return $columns;
    }


    private function findCategory(string $reg_no): string
    {
  
        $stu = Student::find()->where(['student_number' => $reg_no])->one();

        if ($res = $stu?->getCategory()?->one()) {
            return $res->std_category_name;
        }
        return '';
    }

    public function actionPostBeneficiary()
    {
        $columns = $this->generateColumns($this->request->post());

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $ok = false;
            $msg = '';
            foreach ($columns as $column) {
                $model = $this->assign(new BankingSlips(), $column);
                $ok = false;
                if ($model->save()) {
                    $model->refresh();

                    $ok = $model->save();
                } else {
                    break;
                }
            }
            if ($ok) {
                $transaction->commit();
                $msg = 'post action completed successfully!';
                Yii::$app->getSession()->setFlash('new', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => $msg,
                    'closeButton' => null,
                ]);
            } else {
                $msg = 'post action not be completed.';

                Yii::$app->getSession()->setFlash('new', [
                    'type' => Growl::TYPE_DANGER,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => $msg,
                    'closeButton' => null,
                ]);
            }

            return $this->asJson(['status' => $ok, 'msg' => $msg]);
        } catch (\Throwable $th) {
            $transaction->rollBack();

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * List all beneficiary of a fund to be receipted.
     *
     * @return string
     */
    public function actionListBeneficiary()
    {
        $searchModel = new SponsorBeneficiarySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $sourceRef = Yii::$app->request->get();


        return $this->render('list-beneficiary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sourceRef' => $sourceRef['source_reference']
        ]);
    }


    /**
     * Updates an existing ReceiptSponsorFund model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($receipt_sponsor_fund_id)
    {
        $model = $this->findModel($receipt_sponsor_fund_id);

        $request = Yii::$app->request;

        $get = $request->get('sponsor_id');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'sponsor_id' => $get]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * find branch name
     *
     * @return JsonResponse
     */
    public function actionFindBranch()
    {
        try {
            $data = $this->request->post('data');
            $branch = BankAccounts::find()
                ->select(['fss_bank_branches.branch_name', 'fss_bank_branches.branch_code'])
                ->innerJoinWith('branch')
                ->where(['brank_account_id' => $data['bank_account_id']])->asArray()->one();
            return $this->asJson($branch);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
    /**
     * Deletes an existing ReceiptSponsorFund model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($receipt_sponsor_fund_id)
    {
        $this->findModel($receipt_sponsor_fund_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ReceiptSponsorFund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return ReceiptSponsorFund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($receipt_sponsor_fund_id)
    {
        if (($model = ReceiptSponsorFund::findOne(['receipt_sponsor_fund_id' => $receipt_sponsor_fund_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
