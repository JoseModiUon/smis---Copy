<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgInstitutionSetup $model */

$this->title = 'Update Org Institution Setup: ' . $model->institution_setup_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Institution Setups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->institution_setup_id, 'url' => ['view', 'institution_setup_id' => $model->institution_setup_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-institution-setup-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
