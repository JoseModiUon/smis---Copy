<?php

namespace app\modules\feesManagement\traits;

trait FeeTrait
{

    public function assign(Object $model, array $params): Object
    {
        foreach ($params as $key => $value) {
            if (in_array($key, $model->attributes())) {
                $model->{$key} = $value;
            }
        }

        return $model;
    }
}
