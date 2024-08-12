<?php
// Solution From https://stackoverflow.com/a/30501978/19408762
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller as BaseController;
use yii\web\View;

class Controller extends BaseController
{
    /**
     * @inheritdoc
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            Yii::$app->view->on(View::EVENT_BEGIN_BODY, function () {
                $this->fillBreadcrumbs();
            });

            return true;
        } else {
            return false;
        }
    }

    /**
     * Fill common breadcrumbs
     * @throws Exception
     */
    protected function fillBreadcrumbs()
    {
        $breadcrumbs = [];

        // Add needed elements to $breadcrumbs below

        $label = 'Tests';
        $breadcrumbs[] = $this->route == '/tests/default/index' ? $label : [
            'label' => $label,
            'url' => ['/tests/default/index'],
        ];

        // ...

        $this->mergeBreadCrumbs($breadcrumbs);
    }

    /**
     * Prepend common breadcrumbs to existing ones
     * @param array $breadcrumbs
     * @throws Exception
     */
    protected function mergeBreadcrumbs(array $breadcrumbs)
    {
        $existingBreadcrumbs = ArrayHelper::getValue($this->view->params, 'breadcrumbs', []);
        $this->view->params['breadcrumbs'] = array_merge($breadcrumbs, $existingBreadcrumbs);
    }
}
