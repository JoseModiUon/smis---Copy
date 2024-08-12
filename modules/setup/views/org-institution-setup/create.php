<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgInstitutionSetup $model */

$this->title = 'Create Org Institution Setup';
$this->params['breadcrumbs'][] = ['label' => 'Org Institution Setups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-institution-setup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
