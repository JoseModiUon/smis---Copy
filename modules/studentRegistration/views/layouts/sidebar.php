<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

use yii\helpers\Url;
?>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block btn-link">
                    <?=Yii::$app->user->identity->username?>

                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="<?= Url::to(['/student-registration/documents']);?>" class="nav-link">
                        <i class="nav-icon fa fa-file-upload" aria-hidden="true"></i>
                        <p>Uploaded Documents</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= Url::to(['/student-registration/students']);?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-user-graduate" aria-hidden="true"></i>
                        <p>Registered Students</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= Url::to(['/student-registration/registration-documents']);?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-file" aria-hidden="true"></i>
                        <p>Registration Documents</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>