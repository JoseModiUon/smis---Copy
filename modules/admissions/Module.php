<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/24/2023
 * @time: 8:09 AM
 */

declare(strict_types=1);
namespace app\modules\admissions;

use Yii;
use yii\console\Application;

class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'app\modules\admissions\commands';
        }
    }
}