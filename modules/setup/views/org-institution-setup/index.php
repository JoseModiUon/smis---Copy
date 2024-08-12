<?php

use app\models\OrgInstitutionSetup;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\OrgInstitutionSetupSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Org Institution Setups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-institution-setup-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Org Institution Setup', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php //Yii::$app->InstitutionSetup->institution();?>
    <?php
      //  $foo = new \app\components\InstitutionSetup();
     //   $foo->institution();

//        echo $_SESSION['parent_Institution'];
      //  $foo->institution();

//   $session = \Yii::$app->session;
//     echo $session->get('parent_institution_name');
//     echo 'ssssss';
   //  exit();

    ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'institution_setup_id',
            'unit_id',
            'logo_url:url',
            'favicon_url:url',
            'motto:ntext',
            //'email:email',
            //'phone',
            //'website',
            //'postal_address',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, OrgInstitutionSetup $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'institution_setup_id' => $model->institution_setup_id]);
                 }
            ],
        ],
    ]); ?>


</div>
