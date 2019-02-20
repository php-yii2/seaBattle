<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if($isChanged === true){
        ?><div class="alert alert-success">
            Изменения внесены успешно.
        </div>
        <?php
    };?>
    <p>
        В этом разделе вы можете увидеть личную информацию и изменить ее.
    </p>
    <div class="site-contact">
    <?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'firstName') ?>
    <?= $form->field($model, 'lastName') ?>
    <?= $form->field($model, 'email') ?>
    <div class="form-group">
        <div>
            <?= Html::submitButton('Изменить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>
    </div>
</div>
<?php //todo player statistics?>

