<?php
namespace app\modules\studentRecords\components;

use kartik\tabs\TabsX;
use Throwable;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class StudentTabs extends Component
{

    /**
     * @param array $items
     * @param array $options
     * @return string
     */
    public static function main(array $items, array $options=[]): string
    {
        try {
            $default = [
                'items' => $items,
                'position' => TabsX::POS_ABOVE,
                'encodeLabels' => false,
                'bordered' => true,
                'enableStickyTabs' => true,
                'tabContentOptions' => ['class' => 'bg-light'],
                'linkOptions' => ['class' => 'fs-6 fw-bolder '],
                'defaultOptions' => ['class' => 'bg-transparent '],
                'pluginOptions' => ['class' => 'd-print-none '],
                'stickyTabsOptions' => [
                    'selectorAttribute' => 'data-target',
                    'backToTop' => true,
                ],
            ];
            $res = ArrayHelper::merge($default,$options);
            return TabsX::widget($res);
        } catch (Throwable $e) {
            return "<b class='text-danger'>Error:{$e->getMessage()}</b>";
        }
    }
}
