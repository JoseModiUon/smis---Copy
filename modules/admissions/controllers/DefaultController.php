<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/24/2023
 * @time: 8:12 AM
 */

declare(strict_types=1);
namespace app\modules\admissions\controllers;

use app\traits\ControllerTrait;
use JetBrains\PhpStorm\ArrayShape;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

final class DefaultController extends Controller
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
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if(parent::beforeAction($action)) {
            if ($action->id == 'error') {
                $this->layout = 'error';
            }
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'title' => $this->createPageTitle('Admissions')
        ]);
    }
}