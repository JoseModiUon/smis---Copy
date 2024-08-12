<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/9/2024
 * @time: 8:03 PM
 */

namespace app\modules\feesManagement\controllers;

use app\helpers\SmisHelper;
use app\modules\feesManagement\models\BillingFrequency;
use app\modules\feesManagement\models\FeeItem;
use app\modules\feesManagement\models\ProgCurrCharge;
use app\modules\feesManagement\models\search\ItemChargesSearch;
use app\modules\feesManagement\models\search\ProgCurrChargesSearch;
use app\modules\feesManagement\models\search\ProgCurrSearch;
use app\modules\studentRegistration\models\AcademicSession;
use app\modules\studentRegistration\models\ProgrammeLevel;
use app\modules\studentRegistration\models\SemesterCode;
use app\traits\ControllerTrait;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

final class ProgramChargesController extends Controller
{
    use ControllerTrait;

    /**
     * @return string
     */
    public function actionPrograms(): string
    {
        $filterModel = new ProgCurrSearch();
        $programsProvider = $filterModel->search([
            'queryParams' => Yii::$app->request->queryParams,
        ]);

        return $this->render('programs', [
            'title' => $this->createPageTitle('programs'),
            'filterModel' => $filterModel,
            'programsProvider' => $programsProvider,
            'panelHeader' => 'Programs'
        ]);
    }

    /**
     * @param string $progCurrId
     * @return string
     */
    public function actionListCharges(string $progCurrId): string
    {
        $academicYears = $this->academicYears();
        $programs = $this->programs();

        $program = $this->program($progCurrId);
        $regularIntegrated = $program['billing_type_desc'] === 'Regular/Integrated';
        $panelHeader = $program['prog_code'] . ' - ' . $program['prog_short_name'] . ' (' . $program['billing_type_desc'] .
            ' billing type)';

        $filterModel = new ProgCurrChargesSearch();
        $chargesProvider = $filterModel->search([
            'progCurrId' => $progCurrId,
            'regularIntegrated' => $regularIntegrated,
            'queryParams' => Yii::$app->request->queryParams,
        ]);

        return $this->render('index', [
            'title' => $this->createPageTitle('program charges'),
            'filterModel' => $filterModel,
            'chargesProvider' => $chargesProvider,
            'panelHeader' => $panelHeader,
            'regularIntegrated' => $regularIntegrated,
            'academicYears' => $academicYears,
            'programs' => $programs,
            'progCurrId' => $progCurrId
        ]);
    }

    /**
     * @param string $progCurrId
     * @return string
     */
    public function actionCreate(string $progCurrId): string
    {
        $filterModel = new ItemChargesSearch();
        $chargesProvider = $filterModel->search(Yii::$app->request->queryParams);

        $frequencies = BillingFrequency::find()->select(['billing_frequency_id', 'name'])->asArray()->all();
        $academicYears = $this->academicYears();
        $programs = $this->programs();
        $semesterCodes = SemesterCode::find()->asArray()->all();
        $studyLevels = ProgrammeLevel::find()->asArray()->all();

        return $this->render('create', [
            'title' => $this->createPageTitle('add program charge'),
            'filterModel' => $filterModel,
            'chargesProvider' => $chargesProvider,
            'frequencies' => $frequencies,
            'academicYears' => $academicYears,
            'programs' => $programs,
            'semesters' => $semesterCodes,
            'studyLevels' => $studyLevels,
            'progCurrId' => $progCurrId
        ]);
    }

    /**
     * @return Response
     */
    public function actionStore(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            foreach ($post['progCharges'] as $progCharge) {

                $duplicates = $this->checkForChargeDuplicate(
                    [
                        'progCurrId' => $post['progCurrId'],
                        'academicSessId' => $post['acadSessionId'],
                        'feeCode' => $progCharge['feeCode'],
                        'levelOfStudy' => $post['levelOfStudy'],
                        'semester' => $post['semester'],
                    ]
                );

                if (!empty($duplicates['charge'])) {
                    throw new UnprocessableEntityHttpException(
                        'This charge ' . FeeItem::findOne($progCharge['feeCode'])->fee_description .
                        ' is already added to this program for the academic year.'
                    );
                }

                $charge = new ProgCurrCharge();
                $charge->prog_curr_id = $post['progCurrId'];
                $charge->amount_charged = $progCharge['amount'];
                $charge->start_date = SmisHelper::formatDate($progCharge['startDate'], 'Y-m-d');
                $charge->end_date = SmisHelper::formatDate($progCharge['endDate'], 'Y-m-d');
                $charge->fee_code = $progCharge['feeCode'];
                $charge->acad_session_id = $post['acadSessionId'];
                $charge->billing_frequency_id = $progCharge['freq'];

                if ($this->billingType($post['progCurrId'])['billing_type_desc'] === 'Regular/Integrated') {
                    $charge->level_of_study = $post['levelOfStudy'];
                    $charge->semester = $post['semester'];
                }

                if (!$charge->save()) {
                    if (!$charge->validate()) {
                        throw new UnprocessableEntityHttpException(SmisHelper::getModelErrors($charge->getErrors()));
                    } else {
                        throw new ServerErrorHttpException('Failed to add charge of type ' .
                            FeeItem::findOne($progCharge['feeCode'])->fee_description . 'to this program.');
                    }
                }
            }
            $transaction->commit();
            $this->setFlash('success', 'Program charges', 'Charges added successfully.');
            return $this->redirect(['/fees-management/program-charges/list-charges', 'progCurrId' => $post['progCurrId']]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionCopy(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();

            $fromProgBillingType = $this->billingType($post['from-program']);
            $toProgBillingType = $this->billingType($post['to-program']);

            if ($fromProgBillingType !== $toProgBillingType) {
                throw new UnprocessableEntityHttpException('Source and destination Programs billing types don\'t match');
            }

            $sourceCharges = ProgCurrCharge::find()
                ->where([
                    'prog_curr_id' => $post['from-program'],
                    'acad_session_id' => $post['from-year']
                ])->asArray()->all();

            foreach ($sourceCharges as $sourceCharge) {
                $duplicates = $this->checkForChargeDuplicate(
                    [
                        'progCurrId' => $post['to-program'],
                        'academicSessId' => $post['to-year'],
                        'feeCode' => $sourceCharge['fee_code'],
                        'levelOfStudy' => $sourceCharge['level_of_study'],
                        'semester' => $sourceCharge['semester']
                    ]
                );

                if ($duplicates['skipCopy']) {
                    continue;
                }

                $charge = new ProgCurrCharge();
                $charge->prog_curr_id = $post['to-program'];
                $charge->amount_charged = $sourceCharge['amount_charged'];
                $charge->fee_code = $sourceCharge['fee_code'];
                $charge->acad_session_id = $post['to-year'];
                $charge->billing_frequency_id = $sourceCharge['billing_frequency_id'];
                $charge->level_of_study = $sourceCharge['level_of_study'];
                $charge->semester = $sourceCharge['semester'];

                if (!$charge->save()) {
                    if (!$charge->validate()) {
                        throw new UnprocessableEntityHttpException(SmisHelper::getModelErrors($charge->getErrors()));
                    } else {
                        throw new ServerErrorHttpException('Failed to copy charge of type ' .
                            FeeItem::findOne($charge['feeCode'])->fee_description);
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Program charges', 'Charges copied successfully.');
            return $this->redirect(['/fees-management/program-charges/list-charges', 'progCurrId' => $post['to-program']]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @param string $chargeTypeId
     * @return string
     */
    public function actionEdit(string $chargeTypeId): string
    {
        $charge = (new Query())
            ->select([
                'pcc.charge_type_id',
                'pcc.prog_curr_id',
                'pcc.amount_charged',
                'pcc.start_date',
                'pcc.billing_frequency_id',
                'pcc.end_date',
                'sess.acad_session_name',
                'pg.prog_code',
                'pg.prog_short_name',
                'pcc.level_of_study',
                'pcc.semester',
                'bf.name as billing_freq',
                'fi.fee_description',
                'fi.publish'
            ])
            ->from(SMIS_DB_SCHEMA . '.fss_prog_curr_charges pcc')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session sess', 'sess.acad_session_id=pcc.acad_session_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programme_curriculum pc', 'pc.prog_curriculum_id=pcc.prog_curr_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programmes pg', 'pg.prog_id=pc.prog_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_billing_frequency bf', 'bf.billing_frequency_id=pcc.billing_frequency_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_fee_items fi', 'fi.fee_code=pcc.fee_code')
            ->where(['pcc.charge_type_id' => $chargeTypeId])
            ->one();

        return $this->render('edit', [
            'title' => $this->createPageTitle('edit program charge'),
            'charge' => $charge,
            'frequencies' => BillingFrequency::find()->select(['billing_frequency_id', 'name'])->asArray()->all()
        ]);
    }

    /**
     * @return Response
     */
    public function actionUpdate(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $progCurrCharge = ProgCurrCharge::findOne($post['chargeTypeId']);
            $progCurrCharge->amount_charged = $post['amount'];
            $progCurrCharge->billing_frequency_id = $post['freq'];
            $progCurrCharge->start_date = SmisHelper::formatDate($post['start-date'], 'Y-m-d');
            $progCurrCharge->end_date = SmisHelper::formatDate($post['end-date'], 'Y-m-d');
            if (!$progCurrCharge->save()) {
                if (!$progCurrCharge->validate()) {
                    throw new UnprocessableEntityHttpException(SmisHelper::getModelErrors($progCurrCharge->getErrors()));
                } else {
                    throw new ServerErrorHttpException('Failed to update charge of type ' .
                        FeeItem::findOne($progCurrCharge->fee_code)->fee_description);
                }
            }
            $transaction->commit();
            $this->setFlash('success', 'Program charges', 'Charges updated successfully.');
            return $this->redirect(['/fees-management/program-charges/list-charges', 'progCurrId' => $progCurrCharge->prog_curr_id]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionDrop(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            ProgCurrCharge::deleteAll(['charge_type_id' => $post['charges']]);
            $transaction->commit();
            $this->setFlash('success', 'Program charges', 'Charges removed successfully.');
            return $this->redirect(['/fees-management/program-charges/list-charges', 'progCurrId' => $post['progCurrId']]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['charge' => "array|\yii\db\ActiveRecord[]", 'skipCopy' => "bool"])]
    private function checkForChargeDuplicate(array $data): array
    {
        $charge = ProgCurrCharge::find()
            ->where([
                'prog_curr_id' => $data['progCurrId'],
                'acad_session_id' => $data['academicSessId'],
                'fee_code' => $data['feeCode']
            ])->asArray()->all();

        if ($this->billingType($data['progCurrId'])['billing_type_desc'] === 'Regular/Integrated') {
            $charge = ProgCurrCharge::find()
                ->where([
                    'prog_curr_id' => $data['progCurrId'],
                    'acad_session_id' => $data['academicSessId'],
                    'fee_code' => $data['feeCode'],
                    'level_of_study' => $data['levelOfStudy'],
                    'semester' => $data['semester']
                ])->asArray()->all();
        }

        return [
            'charge' => $charge,
            'skipCopy' => !empty($charge)
        ];
    }

    /**
     * @param string $progCurrId
     * @return Response
     */
    public function actionBillingType(string $progCurrId): Response
    {
        $billingType = $this->billingType($progCurrId);
        if (empty($billingType)) {
            return $this->asJson(['success' => false, 'message' => 'No billing type has been set for this program']);
        }
        return $this->asJson(['success' => true, 'billingType' => $billingType['billing_type_desc']]);
    }

    /**
     * @param string $progCurrId
     * @return bool|array
     */
    private function billingType(string $progCurrId): bool|array
    {
        return (new Query())->select(['bt.billing_type_desc'])
            ->from(SMIS_DB_SCHEMA . '.org_programme_curriculum pc')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_billing_type bt', 'bt.billing_type_id=pc.billing_type_id')
            ->where(['pc.prog_curriculum_id' => $progCurrId])
            ->one();
    }

    /**
     * @return array
     */
    private function academicYears(): array
    {
        return AcademicSession::find()->asArray()->all();
    }

    /**
     * @return array
     */
    private function programs(): array
    {
        return (new Query())->select([
            'pc.prog_curriculum_id',
            'prog.prog_code',
            'prog.prog_short_name',
            'bt.billing_type_desc'
        ])
            ->from(SMIS_DB_SCHEMA . '.org_programme_curriculum pc')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programmes prog', 'prog.prog_id=pc.prog_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_billing_type bt', 'bt.billing_type_id=pc.billing_type_id')
            ->where(['pc.status' => 'ACTIVE'])
            ->all();
    }

    /**
     * @param string $progCurrId
     * @return array|bool
     */
    private function program(string $progCurrId): bool|array
    {
        return (new Query())->select([
            'pc.prog_curriculum_id',
            'prog.prog_code',
            'prog.prog_short_name',
            'bt.billing_type_desc'
        ])
            ->from(SMIS_DB_SCHEMA . '.org_programme_curriculum pc')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programmes prog', 'prog.prog_id=pc.prog_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.fss_billing_type bt', 'bt.billing_type_id=pc.billing_type_id')
            ->where(['pc.status' => 'ACTIVE', 'pc.prog_curriculum_id' => $progCurrId])
            ->one();
    }
}