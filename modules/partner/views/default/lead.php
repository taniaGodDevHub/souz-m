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
        <div class="card-body">
            <div class="row">
                <div class="col-12">Карточка клиента</div>
            </div>
            <div class="row mt-3">
                <div class="col-12"><h3><?= Html::encode($clientName) ?> +<?= $lead->client->tel ?> </h3></div>
            </div>
            <div class="row mt-3">
                <div class="col-auto">
                    <div class="row">
                        <div class="col-12 text-muted">ID</div>
                        <div class="col-12"><?= $lead->client->id ?></div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="row">
                        <div class="col-12 text-muted">Дата ДТП</div>
                        <div class="col-12"><?= date("d.m.Y", $lead->date_add) ?></div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="row">
                        <div class="col-12 text-muted">Время ДТП</div>
                        <div class="col-12"><?= date("H:i", $lead->date_add) ?></div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="row">
                        <div class="row">
                            <div class="col-12 text-muted">Город ДТП</div>
                            <div class="col-12"><?= $lead->city->name ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="row">
                        <div class="col-12 text-muted">Страховая компания</div>
                        <div class="col-12"><?= $lead->insuranceCompany->short_name ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12"><h4>Информация об автомобиле</h4></div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-4 text-muted">Марка</div>
                        <div class="col-auto"><?= $lead->carMark->name ?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4 text-muted">Модель</div>
                        <div class="col-auto"><?= $lead->carModel->name ?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4 text-muted">Гос. номер</div>
                        <div class="col-auto"><?= $lead->car_number ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12"><h4>Материалы</h4></div>
                <div class="col-12 text-muted mt-3">Фотографии с места ДТП</div>
                <div class="col-12 mt-3">
                    <div class="row">
                        <?php foreach ($lead->leadFiles as $item) { ?>
                            <?php if ($item->extention == 'pdf') {
                                continue;
                            } ?>
                            <div class="col-4 col-md-2 mb-3">

                                <div class="square-col r-16"
                                     style="
                                             background: url('<?= Yii::getAlias('@web/uploads/' . $item->file_path) ?>');
                                             background-position: center;
                                             background-size: cover;
                                             "
                                ></div>
                            </div>

                        <?php } ?>

                    </div>
                </div>
                <div class="col-12 text-muted mt-3">PDF файлы</div>
                <div class="col-12 mt-3">
                    <div class="row">
                        <?php foreach ($lead->leadFiles as $item) { ?>
                            <?php if ($item->extention != 'pdf') {
                                continue;
                            } ?>
                            <div class="col-4 col-md-2 mb-3">
                                <div class=" square-col r-16">
                                    <div class="square-content">
                                        <div class="r-16 w-100 h-100 d-flex flex-column justify-content-center align-items-center"
                                             style="border: 1px solid #d6d6d6">
                                            <i class="bi bi-filetype-pdf fs-2"></i>
                                            <span class="text-center"><?= $item->name ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12"><h4>Отчёт партнёра</h4></div>
                <div class="col-12">
                    <?= nl2br(Html::encode($lead->report ?? '—')) ?>
                </div>
            </div>
        </div>

    </div>
    <p class="mt-3"><?= Html::a('← К списку заявок', ['leads'], ['class' => 'btn btn-black r-16']) ?></p>
</div>
