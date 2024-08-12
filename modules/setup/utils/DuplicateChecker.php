<?php

namespace app\modules\setup\utils;

use kartik\growl\Growl;
use Yii;

class DuplicateChecker
{
    public $modelObj;
    function buildQuery($columns, $model)
    {
        foreach ($columns as $index => $column) {
            if ($index == 0) {
                $model->where($column);
            } else {
                $model->andWhere($column);
            }
        }
        return $model;
    }

    private function assign($modelObj, $contextObj)
    {
        $name = (new \ReflectionClass(new $modelObj))->getShortName();


        foreach ($contextObj->request->post()[$name] as $key => $value) {
            if (in_array($key, $modelObj->attributes())) {
                $modelObj->$key = $value;
            }
        }

        return empty(array_diff(array_keys($contextObj->request->post()[$name]), $modelObj->attributes()));
    }

    function insert($model, $contextObj, $columns)
    {


        $modelObj = new $model();

        if ($contextObj->request->isPost) {
            if ($this->assign($modelObj, $contextObj)) {

                //check if the same building with same room name exists
                $item = $this->buildQuery($columns, $model::find())->one();

                if (!$item) {
                    if (!$modelObj->save()) {
                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_DANGER,
                            'icon' => 'bi bi-x-circle-fill',
                            'message' => 'Could not add item. Please try again.',
                            'closeButton' => null,
                        ]);
                    }
                    $modelObj->refresh();
                    $this->modelObj = $modelObj;
                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => "Item added successfully",
                        'closeButton' => null,
                    ]);
                    return $contextObj->redirect(['index']);
                } else {
                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_WARNING,
                        'icon' => 'bi bi-exclamation-circle-fill',
                        'message' => 'Room name already in use for selected building. Please provide unique room name.',
                        'closeButton' => null,
                    ]);
                    return $contextObj->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
    }

    function getObjectModel()
    {
        return $this->modelObj ?? null;
    }
}
