<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Activity */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_day')->textInput([
            'type' => 'date',
            'value' => $model -> start_day ? date('Y-m-d', $model -> start_day) : ''])
    ?>

    <?= $form->field($model, 'end_day')->textInput([
            'type' => 'date',
            'value' => $model -> end_day ? date('Y-m-d', $model -> end_day) : ''])
    ?>

    <?= $form->field($model, 'is_repeat')->checkbox() ?>

    <?= $form->field($model, 'is_block')->checkbox() ?>

    <?= $form->field($model, 'body')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
