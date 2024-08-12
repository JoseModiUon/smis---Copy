<?php

namespace app\modules\examManagement\controllers;

use app\modules\examManagement\models\search\AcademicProgressSearch;
use app\modules\examManagement\models\search\DetailedAcademicProgressSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * StudentAcademicProgressController implements the CRUD actions for AcademicProgress model.
 */
class StudentAcademicProgressController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
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
     * Lists all AcademicProgress models.
     *
     * @return string
     */
    public function actionSummarized(): string
    {
        $regNumber = '';

        if (!empty($_GET['registration_number'])) {
            $regNumber = $_GET['registration_number'];
        }

        $searchModel = new AcademicProgressSearch();
        $dataProvider = $searchModel->search($regNumber);

        return $this->render('summarized', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionDetailed(): string
    {
        $regNumber = '';

        if (!empty($_GET['registration_number'])) {
            $regNumber = $_GET['registration_number'];
        }

        $searchModel = new DetailedAcademicProgressSearch();
        $dataProvider = $searchModel->search($regNumber);

        return $this->render('detailed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
