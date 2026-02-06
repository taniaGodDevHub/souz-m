<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserProfileForm $model */
/** @var app\models\UserProfile $profile */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-update">

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-grey r-16">
                <div class="card-body p-4">
                    <?php if (empty($profile->f) && empty($profile->i) && empty($profile->o)): ?>
                        <div class="h3">Заполните данные формы</div>
                    <?php else: ?>
                        <div class="h3"><?= Html::encode($profile->f . ' ' . $profile->i . ' ' . $profile->o) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-12">
            <div class="card card-grey card-shadow r-16">
                <div class="card-body p-4">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6">
                            <?= $this->render('_form', [
                                'model' => $model,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
