<?php

namespace app\modules\examProcessing\controllers;

use app\controllers\BaseController;

/**
 * Default controller for the `exam-processing` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = $this->createPageTitle('Exam processing');
        return $this->render('index');
    }
}
