<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\billing\helpers\BillingHelper;
use app\modules\billing\models\Transaction;

/** @var yii\web\View $this */
/** @var app\modules\billing\models\Account $account */
/** @var app\modules\billing\models\Transaction[] $transactions */

$this->title = 'Счет: ' . $account->name;
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-view">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <h1><?= Html::encode($this->title) ?></h1>
                        <div>
                            <?= Html::a('Редактировать', ['update-account', 'id' => $account->id], ['class' => 'btn btn-warning']) ?>
                            <?= Html::a('Создать транзакцию', ['create-transaction', 'account_id' => $account->id], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5>Информация о счете</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td><?= $account->id ?></td>
                                </tr>
                                <tr>
                                    <th>Название</th>
                                    <td><?= Html::encode($account->name) ?></td>
                                </tr>
                                <tr>
                                    <th>Тип</th>
                                    <td>
                                        <?php if ($account->isSystem()): ?>
                                            <span class="badge bg-secondary">Системный</span>
                                        <?php elseif ($account->isProject()): ?>
                                            <span class="badge bg-info">Проект</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Пользователь</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ($account->user_id): ?>
                                    <tr>
                                        <th>Пользователь</th>
                                        <td><?= Html::encode($account->user->username ?? 'Неизвестно') ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Баланс</h5>
                            <div class="alert alert-<?= $account->getBalanceInRubles() >= 0 ? 'success' : 'danger' ?>" role="alert">
                                <h4 class="alert-heading">
                                    <?= BillingHelper::formatRubles($account->getBalanceInRubles()) ?>
                                </h4>
                                <p class="mb-0">Текущий баланс счета</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>История транзакций</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Тип</th>
                                        <th>От кого</th>
                                        <th>Кому</th>
                                        <th>Сумма</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($transactions)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Нет транзакций</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr>
                                                <td><?= date('d.m.Y H:i', $transaction->date_add) ?></td>
                                                <td><?= Html::encode($transaction->type->name ?? 'Неизвестно') ?></td>
                                                <td>
                                                    <?php if ($transaction->fromAccount): ?>
                                                        <?= Html::encode($transaction->fromAccount->name) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($transaction->toAccount): ?>
                                                        <?= Html::encode($transaction->toAccount->name) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($transaction->to_acc_id == $account->id): ?>
                                                        <span class="text-success">+<?= BillingHelper::formatRubles($transaction->getAmountInRubles()) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-danger">-<?= BillingHelper::formatRubles($transaction->getAmountInRubles()) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?= Html::a('Редактировать', ['update-transaction', 'id' => $transaction->id], ['class' => 'btn btn-sm btn-warning']) ?>
                                                    <?= Html::a('Удалить', ['delete-transaction', 'id' => $transaction->id], [
                                                        'class' => 'btn btn-sm btn-danger',
                                                        'data' => [
                                                            'confirm' => 'Вы уверены, что хотите удалить эту транзакцию?',
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
</div>

