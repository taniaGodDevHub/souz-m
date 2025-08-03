<?php

use app\models\UserProfile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserProfileSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Профили пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user_id',
            [
                'attribute' => 'dealer_id',
                'content' => function ($model) {
                    return !empty($model->dealer) ? $model->dealer->name : 'Нет данных';
                },
                'filter' => \app\models\Dealers::getList()
            ],
            'f',
            'i',
            'o',
            'tel',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, UserProfile $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
