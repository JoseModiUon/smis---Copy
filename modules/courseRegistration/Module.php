<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @updatedAt 16/10/2023 11 am
 */

namespace app\modules\courseRegistration;

use Yii;
use yii\console\Application;

/**
 * studentRecords module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'app\modules\courseRegistration\commands';
        }
    }
}
