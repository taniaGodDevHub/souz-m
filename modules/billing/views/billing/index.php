<?php

use yii\helpers\Html;
use app\modules\billing\helpers\BillingHelper;

/** @var yii\web\View $this */
/** @var app\modules\billing\models\Account[] $accounts */

$this->title = 'Счета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-index">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <h1><?= Html::encode($this->title) ?></h1>
                        <?= Html::a('Создать счет', ['create-account'], ['class' => 'btn btn-success']) ?>
                    </div>
                    
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Баланс</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($accounts)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Нет счетов</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($accounts as $account): ?>
                                        <tr>
                                            <td><?= $account->id ?></td>
                                            <td><?= Html::encode($account->name) ?></td>
                                            <td>
                                                <?php if ($account->isSystem()): ?>
                                                    <span class="badge bg-secondary">Системный</span>
                                                <?php elseif ($account->isProject()): ?>
                                                    <span class="badge bg-info">Проект</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">Пользователь</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong class="<?= $account->getBalanceInRubles() >= 0 ? 'text-success' : 'text-danger' ?>">
                                                    <?= BillingHelper::formatRubles($account->getBalanceInRubles()) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?= Html::a('Просмотр', ['view', 'id' => $account->id], ['class' => 'btn btn-sm btn-primary']) ?>
                                                <?= Html::a('Редактировать', ['update-account', 'id' => $account->id], ['class' => 'btn btn-sm btn-warning']) ?>
                                                <?= Html::a('Удалить', ['delete-account', 'id' => $account->id], [
                                                    'class' => 'btn btn-sm btn-danger',
                                                    'data' => [
                                                        'confirm' => 'Вы уверены, что хотите удалить этот счет?',
                                                        'method' => 'post',
                                                    ],
                                                ]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

