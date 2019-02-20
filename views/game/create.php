<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Создать игру';
$this->params['breadcrumbs'][] = ['label' => 'Игры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="game-form">

        <?php $form = ActiveForm::begin();
        $params = [
            'prompt' => 'Укажите соперника'
        ];
        echo $form->field($model, 'left_gamer')->dropDownList($currentUser);
        echo $form->field($model, 'right_gamer')->dropDownList($competitorList, $params); ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

