<?php

namespace app\helpers;

use yii\helpers\ArrayHelper;

class SmisArrayHelper extends ArrayHelper
{
    /**
     * @param array $data
     * @param string $fieldName
     * @return array
     */
    public static function extractField(array $data, string $fieldName): array
    {
        return array_map(function ($row) use ($fieldName) {
            return $row[$fieldName];
        }, $data);
    }

}
