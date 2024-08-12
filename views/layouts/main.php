<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\FontAwesomeAsset;
use app\modules\studentRegistration\assets\AppAsset;
use app\widgets\Alert;
use kartik\growl\Growl;
use yii\bootstrap5\BootstrapIconAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

FontAwesomeAsset::register($this);
AppAsset::register($this);
BootstrapIconAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web'); ?>/studentreg/img/ndu-arms.png" type="image/x-icon">
    <link rel="icon" href="<?= Yii::getAlias('@web'); ?>/studentreg/img/ndu-arms.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>
    <main>
        <nav class="border-bottom" style="background-color: #d82323;">
            <header class="py-3 border-bottom">
                <div class="container-fluid d-flex justify-content-between fs-5" style="grid-template-columns: 1fr 1fr;">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-decoration-none fw-1 text-white">
                        SMIS
                    </a>

                    <div class="d-flex justify-content-end">
                        <div class="flex-shrink-0 dropdown w-100 me-3">
                            <a href="javascript:void(0);" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-bounding-box"></i> <?= Yii::$app->user->identity->username ?>
                            </a>
                            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                                <li>
                                    <?= Html::a(
                                        'Change password',
                                        Yii::$app->params['resetPasswordUrl'],
                                        [
                                            'title' => 'Change password',
                                            'class' => 'dropdown-item btn-link',
                                        ]
                                    )
                                    ?>
                                </li>
                                <li>
                                    <?= Html::a(
                                        'Sign Out',
                                        ['/site/logout'],
                                        [
                                            'class' => 'dropdown-item btn-link',
                                            'data' => ['method' => 'post']
                                        ]
                                    )
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
        </nav>
        <div class="container-fluid">
            <?php
            try {
                echo Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'] ?? [],
                ]);

                echo Alert::widget();

                echo $content;
            } catch (Throwable $e) {
            }
            ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-white font-weight-bold" style="background-color: #304186">
        <div class="container ">
            <span class="float-start">
                &copy;<?= date('Y') . ' ' . Yii::$app->params['orgName'] ?> | PRUDENTIA, EXCELLENCIA ET OPERA
            </span>
        </div>
    </footer>

    <?php
    $flashType = '';
    $flashTitle = '';
    $flashIcon = '';
    $flashes = Yii::$app->session->getAllFlashes();
    if (!empty($flashes)) {
        if (!empty($flashes['new'])) {
            $flashMessage = $flashes['new']['message'];

            if ($flashes['new']['type'] === 'success') {
                $flashType = Growl::TYPE_SUCCESS;
                $flashTitle = 'Well done!';
                $flashIcon = 'fa fa-check-circle';
            }

            if ($flashes['new']['type'] === 'danger') {
                $flashType = Growl::TYPE_DANGER;
                $flashTitle = 'Oh snap!';
                $flashIcon = 'fa fa-times-circle';
            }

            try {
                echo Growl::widget([
                    'type' => $flashType,
                    'title' => $flashTitle,
                    'icon' => $flashIcon,
                    'body' => $flashMessage,
                    'showSeparator' => true,
                    'delay' => 0,
                    'closeButton' => null,
                    'pluginOptions' => [
                        'showProgressbar' => false,
                        'placement' => [
                            'from' => 'bottom',
                            'align' => 'right',
                        ]
                    ]
                ]);
            } catch (Exception $e) {
            }
        }

        if (!empty($flashes['added'])) {
            foreach ($flashes['added'] as $addedFlash) {
                $flashMessage = $addedFlash['message'];
                if ($addedFlash['type'] === 'success') {
                    $flashType = Growl::TYPE_SUCCESS;
                    $flashTitle = 'Well done!';
                    $flashIcon = 'fa fa-check-circle';
                }
                if ($addedFlash['type'] === 'danger') {
                    $flashType = Growl::TYPE_DANGER;
                    $flashTitle = 'Oh snap!';
                    $flashIcon = 'fa fa-times-circle';
                }

                try {
                    echo Growl::widget([
                        'type' => $flashType,
                        'title' => $flashTitle,
                        'icon' => $flashIcon,
                        'body' => $flashMessage,
                        'showSeparator' => true,
                        'delay' => 0,
                        'pluginOptions' => [
                            'showProgressbar' => false,
                            'placement' => [
                                'from' => 'bottom',
                                'align' => 'right',
                            ]
                        ]
                    ]);
                } catch (Exception $e) {
                }
            }
        }
    }
    ?>

    <?php
    $this->endBody()
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

</body>

</html>
<?php $this->endPage() ?>