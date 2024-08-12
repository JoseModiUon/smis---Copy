<?php

namespace app\modules\studentRecords\controllers;

use yii\web\Controller;

/**
 * Default controller for the `studentRecords` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
