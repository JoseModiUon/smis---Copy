<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\controllers;

use Exception;
use Yii;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

class BaseController extends Controller
{
    /**
     * Setup controllers with initial data
     * @return void
     * @throws ServerErrorHttpException
     */
    public function init(): void
    {
        try {
            parent::init();
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
}
