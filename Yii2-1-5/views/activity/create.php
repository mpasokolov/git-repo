<?php
/**
 * @var $model \app\models\CreateActivityForm
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<div class="createActivity">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form -> field($model, 'name') -> textInput() ?>
    <?= $form -> field($model, 'start') -> textInput(['type' => 'date']) ?>
    <?= $form -> field($model, 'end') -> textInput(['type' => 'date']) ?>
    <?= $form -> field($model, 'block') -> checkbox() ?>
    <?= $form -> field($model, 'repeat') -> checkbox() ?>
    <?= $form -> field($model, 'text') -> textarea() ?>
    <?= $form -> field($model, 'activityFiles[]') -> fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end() ?>
</div>
