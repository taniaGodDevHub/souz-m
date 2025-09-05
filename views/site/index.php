<?php

/** @var yii\web\View $this */

$this->title = 'crm.eds-systems';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">crm.souz-m</h1>

        <p class="lead">
        <div class="col-md-4 col-12 text-center">Добрый день! </div>

            <?php if(!Yii::$app->user->isGuest): ?>
                <div class="col-md-4 col-12 text-center">При первом входе не забудьте заполнить профиль :)</div>
                <div class="col-md-4 col-12 text-center">
                    <a class="btn btn-success" href="<?= Url::to(['/user-profile/update', 'user_id' => Yii::$app->user->identity->id])?>">Перейти в профиль</a>
                </div>
            <?php endif;?>
        </p>

    </div>
</div>
