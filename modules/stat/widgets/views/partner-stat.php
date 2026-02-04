<?php
/** @var yii\web\View $this */
?>
<div class="stat-partner-stat-widget">
    <div class="row justify-content-between">
        <div class="col-auto">
            <h3>Статистика за <span class="text-decoration-underline">декабрь 2025</span></h3>
        </div>
        <div class="col-auto">
            <div class="row">
                <div class="col-auto">
                    <div class="badge bg-secondary text-normal r-16">
                        <img src="<?= Yii::getAlias('@web/img/calendar.svg')?>" alt="">
                        <span class="ms-1">Январь</span>
                    </div>
                </div>
                <div class="col-auto pe-0">
                    <div class="badge bg-secondary text-normal r-50-p p-1">
                        <img src="<?= Yii::getAlias('@web/img/chevron-left.svg')?>" alt="">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="badge bg-secondary text-normal r-50-p p-1">
                        <img src="<?= Yii::getAlias('@web/img/chevron-right.svg')?>" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card card-white h-100">
                <div class="card-body p-4 pb-0 pe-0">
                    <div class="row h-100 flex-column justify-content-between">
                        <div class="col-12">
                            <div class="row justify-content-start">
                                <div class="col-auto text-muted">
                                    Оформлений
                                </div>
                            </div>
                            <div class="row justify-content-start mt-3">
                                <div class="col-auto">
                                    <h3>0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-end">
                                <div class="col-6">
                                    <img class="img-fluid" src="<?= Yii::getAlias('@web/img/chart-blue.svg')?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-white h-100">
                <div class="card-body p-4 pb-0 pe-0">
                    <div class="row h-100 flex-column justify-content-between">
                        <div class="col-12">
                            <div class="row justify-content-start">
                                <div class="col-auto text-muted">
                                    Заработано
                                </div>
                            </div>
                            <div class="row justify-content-start mt-3">
                                <div class="col-auto">
                                    <h3>0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-end">
                                <div class="col-6">
                                    <img class="img-fluid" src="<?= Yii::getAlias('@web/img/chart-red.svg')?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-white h-100">
                <div class="card-body p-4 pb-0">
                    <div class="row h-100 flex-column justify-content-between">
                        <div class="col-12">
                            <div class="row justify-content-start">
                                <div class="col-auto text-muted">
                                    Осталось до бонуса
                                </div>
                            </div>
                            <div class="row justify-content-start mt-3">
                                <div class="col-auto">
                                    <h3>0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-start" style="margin-left: -1.5rem">
                                <div class="col-8 ps-0">
                                    <img class="img-fluid" src="<?= Yii::getAlias('@web/img/chart-yellow.svg')?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
