<?php

/** author Jeff Rureri <wahome4jeff@gmail.com>
 * Date: 30/10/2023
*/

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgSponsor $model */

$this->title = 'Create  Sponsor ';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
            <div class="card">
                <div class="container">
                    <div class="page-header"><br>
                        <h4 class="text-center"><?= Html::encode($this->title) ?></h4>
                        <?= $this->render('_form', [
                            'model' => $model,
                        ]) ?>
                    </div>
                </div>
            </div>
            
            </div>
        </div>
    </div>
</section>

