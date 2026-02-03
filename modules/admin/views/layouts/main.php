<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

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
<header id="header">
    <?php
    NavBar::begin([
        'brandImage' => Yii::getAlias('@web/img/logo.svg'),
        'brandLabel' => 'Кабинет администратора',
        'brandUrl' => ['/admin/default/index'],
        'options' => ['class' => 'navbar-expand-md fixed-top'],
        'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav bg-white'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Главная', 'url' => ['/admin/default/index']],
            [
                'label' => 'Пользователи',
                'items' => [
                    ['label' => 'Управление пользователями', 'url' => ['/users/users/index']],
                    ['label' => 'Профили пользователей', 'url' => ['/user-profile/index']],
                    ['label' => 'Роли и разрешения', 'url' => ['/rbac/auth-item/index']],
                    ['label' => 'Наследования', 'url' => ['/rbac/auth-item-child/index']],
                ],
            ],
            [
                'label' => 'Списки',
                'items' => [
                    ['label' => 'Города', 'url' => ['/city/index']],
                    ['label' => 'Страховые компании', 'url' => ['/insurance_companies/insurance-company/index']],
                    ['label' => 'Марки автомобилей', 'url' => ['/cars/car-mark/index']],
                    ['label' => 'Модели автомобилей', 'url' => ['/cars/car-model/index']],
                ],
            ],
            ['label' => 'Биллинг', 'url' => ['/billing/billing/index']],
            '<li class="nav-item">' . Html::beginForm(['/site/logout'])
                . Html::submitButton('Выйти (' . Yii::$app->user->identity->username . ')', ['class' => 'nav-link btn btn-link logout'])
                . Html::endForm() . '</li>',
        ],
    ]);
    NavBar::end();
    ?>
</header>
<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
