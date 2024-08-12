<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\controllers;

use app\modules\studentRegistration\models\search\RegisteredStudentsSearch;
use app\traits\ControllerTrait;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;

final class StudentsController extends BaseController
{
    use ControllerTrait;

    /**
     * {@inheritdoc}
     */
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
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $regStudentsSearchModel = new RegisteredStudentsSearch();
        $regStudentsDataProvider = $regStudentsSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'title' => $this->createPageTitle('students'),
            'regStudentsSearchModel' => $regStudentsSearchModel,
            'regStudentsDataProvider' => $regStudentsDataProvider
        ]);
    }
}
