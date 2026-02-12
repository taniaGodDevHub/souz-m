<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserProfileForm $model */
/** @var app\models\UserProfile $profile */
/** @var app\models\Requisites[] $requisites */
/** @var app\models\Requisites|null $requisitesForm */
/** @var bool $activateRequisitesTab */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-update">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-grey r-16">
                <div class="card-body p-5">
                    <?php if (empty($profile->f) && empty($profile->i) && empty($profile->o)): ?>
                        <div class="col-12">
                            <div class="h3">Заполните данные формы</div>
                        </div>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="h3"><?= Html::encode($profile->f . ' ' . $profile->i . ' ' . $profile->o) ?></div>
                        </div>
                        <div class="col-12 text-muted">
                            @<?= $profile->user->tg_login ? $profile->user->tg_login : '' ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                            class="nav-link active"
                            id="profile-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#profile-tab-pane"
                            type="button" role="tab"
                            aria-controls="profile-tab-pane"
                            aria-selected="true">Данные пользователя</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                            class="nav-link"
                            id="requisites-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#requisites-tab-pane"
                            type="button" role="tab"
                            aria-controls="requisites-tab-pane"
                            aria-selected="false">Реквизиты</button>
                </li>
            </ul>
            <div class="tab-content pt-4" id="myTabContent">
                <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
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
                <div class="tab-pane fade" id="requisites-tab-pane" role="tabpanel" aria-labelledby="requisites-tab" tabindex="0">
                    <?php if (!empty($requisites)): ?>
                        <?php foreach ($requisites as $req): ?>
                            <?= $this->render('_requisites_card', ['requisites' => $req]) ?>
                        <?php endforeach; ?>
                    <?php elseif ($requisitesForm !== null): ?>
                        <div class="card card-grey card-shadow r-16">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="mb-4">Добавление реквизитов</h3>
                                <?= $this->render('_requisites_form', ['model' => $requisitesForm]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($activateRequisitesTab ?? false)): ?>
<script>
(function(){
    var tab = document.querySelector('#requisites-tab');
    var pane = document.querySelector('#requisites-tab-pane');
    if (tab && pane) {
        tab.classList.add('active');
        document.querySelector('#profile-tab').classList.remove('active');
        pane.classList.add('show', 'active');
        document.querySelector('#profile-tab-pane').classList.remove('show', 'active');
    }
})();
</script>
<?php endif; ?>