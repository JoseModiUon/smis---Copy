<?php

namespace app\modules\functionalSetup;
use Yii;

/**
 * functionalSetup module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\functionalSetup\controllers';

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
