<?php

use app\models\OrgRooms;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\OrgRoomsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rooms';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-rooms-index">



    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Room',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>
            </div>
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            </div>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    //   ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'kartik\grid\SerialColumn'],
                    //'room_id',
                    'room_code',
                    'room_name',
                    // 'fk_building_id',
                    [
                        'attribute' => 'building',
                        'label' => 'Building',
                        'value' => 'building.building_name',
                    ],

                    [
                        'attribute' => 'fkFloor',
                        'label' => 'Floor',
                        'value' => 'fkFloor.floor_name',
                    ],

                    'room_capacity',
                    // 'fk_room_type',
                    [
                        'attribute' => 'roomType',
                        'label' => 'Room Type',
                        'value' => 'roomType.room_type_name',
                    ],

                    [
                        'attribute' => 'fkRoomStatus',
                        'label' => 'Room Status',
                        'value' => 'fkRoomStatus.room_status_desc',
                    ],


                    /* [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, OrgRooms $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'room_id' => $model->room_id]);
                 }
            ],*/
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-rooms/update', 'room_id' => $model->room_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>