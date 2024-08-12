<?php

namespace app\modules\setup;

use Yii;

/**
 * setup module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\setup\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->setLayoutPath(Yii::$app->layoutPath);
        $this->layout = 'main-contained';
    }
}
