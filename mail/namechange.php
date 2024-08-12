<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */


?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
                <p>Hallo <?=$name?></p>
                <p>Congratulations on your successful name change request</p>
                <div class="d-flex justify-content-end">
                    <?=
                        Html::a(
                            ' Download Document',
                            ['/student-records/sm-name-change/letter-download',
                            'document_url' => $url,'file' => $reg_no.'.pdf'],
                            ['class' => ' bi bi-download btn btn-outline-primary btn-sm']
                        );
?>
                </div>
                <p>Regards</p>
            </div>
        </div>
    </div>
</div>