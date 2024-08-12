<?php

/**
 * @class BaseController
 *
 * contains logic common to all controllers
 */

namespace app\controllers;

use app\modules\setup\controllers\DefaultController;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use yii\web\View;

class BaseController extends Controller
{
    /**
     * Setup controllers with initial data
     * @return void
     * @throws ServerErrorHttpException
     */
    public function init(): void
    {
        try{
            parent::init();

        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @inheritdoc
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            Yii::$app->view->on(View::EVENT_BEGIN_BODY, function () {
                $this->fillBreadcrumbs();
            });

            return true;
        } else {
            return false;
        }
    }

    /**
     * Fill common breadcrumbs
     * @throws Exception
     */
    protected function fillBreadcrumbs()
    {
        if(str_contains(Yii::$app->request->pathInfo, '/')) {
            $breadcrumbs = [];
            //Add needed elements to $breadcrumbs below

            $array = explode("/", Yii::$app->request->pathInfo);

            array_pop($array);

            $module = $array[0];

            $label = ucwords($module);
            $breadcrumbs[] = $this->route == '/tests/default/index' ? $label : [
                'label' => $label,
                'url' => Url::to([DefaultController::class, 'index']),
            ];

            $this->mergeBreadCrumbs($breadcrumbs);
        }
    }

    /**
     * Prepend common breadcrumbs to existing ones
     * @param array $breadcrumbs
     * @throws Exception
     */
    protected function mergeBreadcrumbs(array $breadcrumbs)
    {
        $existingBreadcrumbs = ArrayHelper::getValue($this->view->params, 'breadcrumbs', []);
        $this->view->params['breadcrumbs'] = array_merge($breadcrumbs, $existingBreadcrumbs);
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function setFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->setFlash('new', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function addFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->addFlash('added', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * Create the page title
     * @param string $title
     * @return string full page title
     */
    protected function createPageTitle(string $title): string
    {
        return Yii::$app->params['sitename'] . ' - ' . $title;
    }
}
