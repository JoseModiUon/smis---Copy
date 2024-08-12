<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @desc This file displays the error page with messages
 */

/**
 * @var $this yii\web\View
 * @var string $name
 * @var string $title
 */

use yii\helpers\Url;

$this->title = 'Error | ' . $name;
$exception = Yii::$app->errorHandler->exception;

$supportEmail = Yii::$app->params['supportEmail'];
?>

<h5><?= $exception->getMessage() ?></h5>

<p>
    <br/>
    <br/>
    Do you need help? Send us an email
    <a href="mailto:<?=$supportEmail?>"><?= $supportEmail?></a>
    <br/>
    <br/>
    <a href="<?= Url::to(['/site']) ?>"> go home</a> or
    <a href="<?= Url::to(Yii::$app->request->referrer) ?>">return to previous page</a>
</p>

