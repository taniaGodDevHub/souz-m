<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserProfile $profile */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update'], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="card card-grey r-16 p-4">
        <p><strong>ФИО:</strong> <?= Html::encode(trim($profile->f . ' ' . $profile->i . ' ' . $profile->o)) ?: '—' ?></p>
        <p><strong>Телефон:</strong> <?= Html::encode($profile->tel ?? ($profile->user->tel ?? '—')) ?></p>
        <p><strong>Telegram:</strong> <?= Html::encode($profile->user->tg_login ?? '—') ?></p>
    </div>

</div>
