<?php

/** @var yii\web\View $this */

$this->title = 'SMIS';

$menuList = require __DIR__ . '/menu-list.php';
?>
<div class="container bg-light mt-5">
    <div class="row align-items-md-stretch px-md-5 row-cols-1 row-cols-lg-5 h-auto pt-md-5 text-primary">
        <?php
        foreach ($menuList as $menu) :
            $m = (object)$menu;
        ?>
            <div class="col-md-3 col-sm-4 col-xs-6 <?= $m->active ? "aktv" : "not-active" ?>">
                <a style="text-decoration: none;" <?= $m->active ? '' : 'class="text-muted"' ?> href="<?= $m->active ? $m->rl : 'javascript:void(0)' ?>">
                    <div class="row text-center">
                        <div style="padding: 10px;">
                            <i class="bi bi-<?= $m->icon ?> fs-1"></i>
                            <h4><?= $m->name ?></h4>
                        </div>
                    </div>
                </a>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</div>