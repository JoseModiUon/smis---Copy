<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/16/2023
 * @time: 11:35 AM
 */
declare(strict_types=1);
namespace app\traits;

use Yii;

trait ControllerTrait
{
    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function setFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->setFlash('new', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function addFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->addFlash('added', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * Create the page title
     * @param string $title
     * @return string full page title
     */
    protected function createPageTitle(string $title): string
    {
        return Yii::$app->params['sitename'] . ' - ' . $title;
    }
}
