<?php

namespace app\modules\studentid;

use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application;

/**
 * studentid module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\studentid\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\studentid\commands';
        }
    }
}
