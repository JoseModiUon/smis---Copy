<?php

namespace app\modules\setup\utils;

// we'll receive an array of the model objects used
class AutoSynchronize
{
    public function __construct(private array $models = [])
    {
    }


    public function save(): bool
    {
        foreach ($this->models as $model) {
            $name = (new \ReflectionClass($model))->getShortName();
            $class = 'app\models\portal_student\\' . $name;
            $portalModel = $this->assign($class, $model);
            $ok = $portalModel->save();
            if (!$ok) {
                dd([$portalModel->getErrors()]);
                throw new \Exception("failed to sync $name");
            }
        }
        return $ok;
    }

    private function assign($model, $modelAdmin): Object
    {
        if (!is_object($model)) {
            $model = new $model();
        }

        foreach ($modelAdmin->getAttributes() as $key => $value) {
            if (in_array($key, $model->attributes())) {
                $model->{$key} = $value;
            }
        }
        return $model;
    }

    public function bulkSync($modelClass)
    {
        $name = (new \ReflectionClass(new $modelClass))->getShortName();

        $adminClass = 'app\models\\' . $name;
        $models = $adminClass::find()->all();

        foreach ($models as $model) {
            $pk = $model->tableSchema->primaryKey[0];
            $portalClass = 'app\models\portal_student\\' . $name;

            $modelPortal = $portalClass::findOne($model->$pk);
            if (!$modelPortal) {
                $modelPortal = new $portalClass();
            }

            $portalModel = $this->assign($modelPortal, $model);
            $ok = $portalModel->save();
            if (!$ok) {
                // dd([$portalModel->getErrors()]);
                throw new \Exception("failed to sync $modelClass");
            }
        }
        return $ok;
    }


    public function bulkSyncPortal($modelClass)
    {

        $name = (new \ReflectionClass(new $modelClass))->getShortName();
        $portalClass = 'app\models\portal_student\\' . $name;

        $models = $portalClass::find()->all();

        $pk = $this->findPK('app\models\\' . $name);

        foreach ($models as $model) {
            $adminClass = 'app\models\\' . $name;
            $modelAdmin = $adminClass::findOne($model->$pk);
            if (!$modelAdmin) {
                $modelAdmin = new $adminClass();
            }

            $admin = $this->assign($modelAdmin, $model);
            $ok = $admin->save();
            if (!$ok) {
                throw new \Exception("failed to sync $modelClass");
            }
        }
        return $ok;
    }

    private function findPK($modelClass)
    {
        $model = new $modelClass();
        return $model->tableSchema->primaryKey[0];
    }
}
