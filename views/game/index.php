<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Игры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать игру', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'label'=>'id игры'
            ],
            [
                'attribute' => 'left_gamer',
                'value' => 'leftGamer.firstName',
                'label'=>'Игрок слева'
            ],
            [
                'attribute' => 'right_gamer',
                'value' => 'rightGamer.firstName',
                'label'=>'Игрок справа'
            ],
            [
                'attribute' => 'attack_side',
                'label'=>'Следующий ход'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('Начать игру', '?r=game/start&id='.$model->id);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
