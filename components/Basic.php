<?php

namespace app\components;

use yii\base\Component;
use yii\base\Model;

class Basic extends Component
{
    /**
     * Package and Style Model Error
     * @param $model Model
     * @param $delimeter string|null
     * @return string
     */
    public static function modelError($model, $delimeter='<br/>'){
        $valueList = array_values($model->getFirstErrors());
        switch ($delimeter){
            case '<br/>':case ',':case '||':
            $res = implode($delimeter, $valueList);
                break;
            default:
                $res= "<$delimeter>".implode("</$delimeter><$delimeter>", $valueList)."</$delimeter>";
                break;
        }
        return $res;
    }

    /**
     * Fix for: Yii2 converts NULL to string before beforeSave
     * @param $v1 string|null
     * @param $v2 string|null
     * @return bool
     */
    public static function notEmpty(?string $v1, ?string $v2){
        // Check Nulls or empty
        if($v1=='' && $v2==''){
            return $v1==$v2;
        }
        return false;
    }
}
