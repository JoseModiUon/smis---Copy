<?php

namespace app\models\traits;

trait ModelTrait
{
    public function assign(array $params)
    {
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                if (array_key_exists($key, $this->getAttributes())) {
                    $this->$key = $val;
                }
            }
            return true;
        }
        return false;
    }
}
