<?php
/** author Jeff Rureri <wahome4jeff@gmail.com>
 * Date: 30/10/2023
*/

namespace app\modules\setup\controllers;

use app\models\OrgSponsor;
use app\models\search\SponsorSearch;
use app\traits\ControllerTrait;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * SponsorController implements the CRUD actions for OrgSponsor model.
 */
class SponsorController extends Controller
{
    use ControllerTrait;

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
     * Lists all OrgSponsor models.
     *
     * @return string
     */
    public function actionIndex()
    {
        try {
            $searchModel = new SponsorSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }

    }

    /**
     * Displays a single OrgSponsor model.
     * @param int $sponsor_id Sponsor ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sponsor_id)
    {
        try {
            return $this->render('view', [
                'model' => $this->findModel($sponsor_id),
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }

    }

    /**
     * Creates a new OrgSponsor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        try {
            $model = new OrgSponsor();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    $this->setFlash('success', 'Create Sponsor', 'Sponsor record created successfully.');
                    return $this->redirect(['index']);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }

    }

    /**
     * Updates an existing OrgSponsor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sponsor_id Sponsor ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sponsor_id)
    {
        try {
            $model = $this->findModel($sponsor_id);

            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                $this->setFlash('success', 'Update Sponsor', 'Sponsor record updated successfully.');
                return $this->redirect(['index']);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }

    }

    /**
     * Deletes an existing OrgSponsor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sponsor_id Sponsor ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sponsor_id)
    {
        try {
            $this->findModel($sponsor_id)->delete();

            return $this->redirect(['index']);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }

    }

    /**
     * Finds the OrgSponsor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sponsor_id Sponsor ID
     * @return OrgSponsor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sponsor_id)
    {
        if (($model = OrgSponsor::findOne(['sponsor_id' => $sponsor_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
