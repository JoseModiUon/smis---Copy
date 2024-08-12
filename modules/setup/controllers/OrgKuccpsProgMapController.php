<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\OrgKuccpsProgMap;
use yii\web\NotFoundHttpException;
use app\models\search\OrgKuccpsProgMapSearch;

/**
 * OrgKuccpsProgMapController implements the CRUD actions for OrgKuccpsProgMap model.
 */
class OrgKuccpsProgMapController extends Controller
{
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
     * Lists all OrgKuccpsProgMap models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgKuccpsProgMapSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgKuccpsProgMap model.
     * @param int $prog_map_id Prog Map ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_map_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_map_id),
        ]);
    }

    /**
     * Creates a new OrgKuccpsProgMap model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgKuccpsProgMap();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', " Program Map {$model->kuccps_prog_name} Created!");
                return $this->redirect('index');
            }var_dump($model->getErrors());
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new OrgKuccpsProgMap model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionBatch()
    {
        $model = new OrgKuccpsProgMap();

        if (Yii::$app->request->isPost) {
            $model->batchFile = UploadedFile::getInstance($model, 'batchFile');
            if ($model->upload($this->getBatchData($model))) {
                Yii::$app->getSession()->setFlash('success', " Program Map {$model->kuccps_prog_name} Created!");
                return $this->redirect('index');

            }else{
                var_dump($model->getErrors());
                $model->loadDefaultValues();

            }
        }

        return $this->render('batch-create', [
            'model' => $model,
        ]);
    }
    
    private function getBatchData(OrgKuccpsProgMap $model) :array {
        $data = [];
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($model->batchFile->tempName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        if (!empty($sheetData)) {
            for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                $data[] = [
                    'kuccps_prog_code' => $sheetData[$i][0],
                    'kuccps_prog_name' => $sheetData[$i][1],
                    'uon_prog_code' => $sheetData[$i][2],
                ];
            }
        }
        return $data;
    }
    /**
     * Updates an existing OrgKuccpsProgMap model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_map_id Prog Map ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_map_id)
    {
        $model = $this->findModel($prog_map_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', " Program Map {$model->kuccps_prog_name} Updated!");
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgKuccpsProgMap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_map_id Prog Map ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_map_id)
    {
        $this->findModel($prog_map_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgKuccpsProgMap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_map_id Prog Map ID
     * @return OrgKuccpsProgMap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_map_id)
    {
        if (($model = OrgKuccpsProgMap::findOne(['prog_map_id' => $prog_map_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
