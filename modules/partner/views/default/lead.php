<?php
/** @var yii\web\View $this */
/** @var app\models\Lead $lead */

use yii\bootstrap5\Html;

$this->title = 'Заявка №' . $lead->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['leads']];
$this->params['breadcrumbs'][] = $this->title;

$clientName = '—';
if ($lead->client && $lead->client->profile) {
    $p = $lead->client->profile;
    $clientName = trim(($p->f ?? '') . ' ' . ($p->i ?? '') . ' ' . ($p->o ?? '')) ?: '—';
}
?>
<div class="partner-default-lead">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="card card-shadow card-grey r-16 p-4 mt-3">
        <p><strong>Клиент:</strong> <?= Html::encode($clientName) ?></p>
        <p><strong>Марка:</strong> <?= Html::encode($lead->carMark->name ?? '—') ?></p>
        <p><strong>Модель:</strong> <?= Html::encode($lead->carModel->name ?? '—') ?></p>
        <p><strong>Статус:</strong> <?= Html::encode($lead->status->name ?? '—') ?></p>
        <p><strong>Отчёт:</strong> <?= nl2br(Html::encode($lead->report ?? '—')) ?></p>
    </div>
    <p class="mt-3"><?= Html::a('← К списку заявок', ['leads'], ['class' => 'btn btn-secondary']) ?></p>
</div>
