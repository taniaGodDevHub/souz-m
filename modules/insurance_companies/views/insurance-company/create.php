<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\insurance_companies\models\InsuranceCompany $model */

$this->title = 'Создать страховую компанию';
$this->params['breadcrumbs'][] = ['label' => 'Страховые компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-create">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

