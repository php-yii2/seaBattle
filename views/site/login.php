<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Войти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <div class="form-group">
        <?= Html::submitButton('Войти', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="jumbotron">
        <p class="lead">Нет аккаунта?</p>
        <?= Html::a('Регистрция', ['/site/registration'], ['class'=>'btn btn-success'])?>
    </div>
</div>