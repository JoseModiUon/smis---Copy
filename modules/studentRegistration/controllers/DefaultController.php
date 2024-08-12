<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\controllers;

use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;

class DefaultController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    # [ArrayShape(['access' => "array"])]
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
    # [ArrayShape(['error' => "string[]"])]
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

    public function actionIndex(): string
    {
        return $this->render('index');
//        $this->redirect(['/student-registration/documents']);
    }
}
