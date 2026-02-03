<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<header id="header" class="pt-3 ps-3 pe-3">
    <div class="row justify-content-between">
        <div class="col-md-auto">
            <a class="navbar-brand" href="<?= URL::to(['/partner/default/index'])?>">
                <img src="<?= Yii::getAlias('@web/img/logo.svg')?>" alt="Кабинет партнёра">
            </a>
        </div>
        <div class="container-width">
            <div class="row align-items-end h-100">
                <div class="col-auto pb-3 nav-link-col">
                    <a class="nav-link" href="<?= URL::to(['/partner/default/index'])?>">Главная</a>
                    <span class="nav-link-col-border"></span>
                </div>
                <div class="col-auto pb-3 nav-link-col">
                    <a class="nav-link" href="<?= URL::to(['/partner/default/leads'])?>">Заявки</a>
                    <span class="nav-link-col-border"></span>
                </div>
                <div class="col-auto pb-3 nav-link-col">
                    <a class="nav-link" href="<?= URL::to(['/partner/default/billing'])?>">Финансы</a>
                    <span class="nav-link-col-border"></span>
                </div>
            </div>
        </div>
        <div class="col-md-auto">
            <?= Yii::$app->user->isGuest
                ? [
                    'label' => Html::img(Yii::getAlias('@web/img/exit-btn.svg')),
                    'url' => ['/site/login']
                ]
                : '<li class="nav-item">'
                . Html::beginForm(['/site/logout'])
                . Html::submitButton(
                    Html::img(Yii::getAlias('@web/img/exit-btn.svg')),
                    ['class' => 'nav-link btn logout']
                )
                . Html::endForm()
                . '</li>'?>
        </div>
    </div>
</header>
<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
