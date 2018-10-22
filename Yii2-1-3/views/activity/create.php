<?php
/**
 * @var $model \app\models\CreateActivityForm
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<div class="createActivity">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form -> field($model, 'title') -> textInput() ?>
    <?= $form -> field($model, 'startDay') -> textInput(['type' => 'date']) ?>
    <?= $form -> field($model, 'endDay') -> textInput(['type' => 'date']) ?>
    <?= $form -> field($model, 'isBlock') -> checkbox() ?>
    <?= $form -> field($model, 'isRepeat') -> checkbox() ?>
    <?= $form -> field($model, 'body') -> textarea() ?>
    <?= $form -> field($model, 'activityFiles[]') -> fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end() ?>
</div>
