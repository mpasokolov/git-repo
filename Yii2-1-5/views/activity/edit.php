<?php
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Html;
?>
<div class="edit-activity">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['value' => $data['title']]); ?>
    <?= $form->field($model, 'start')->textInput(['type' => 'date', 'value' => $data['start_day']]); ?>
    <?= $form->field($model, 'end')->textInput(['type' => 'date', 'value' => $data['end_day']]); ?>
    <?= $form->field($model, 'repeat')->checkbox(['checked' => $data['is_repeat'] ? true : false]); ?>
    <?= $form->field($model, 'block')->checkbox(['checked' => $data['is_block'] ? true : false]); ?>
    <?= $form->field($model, 'text')->textarea(['value' => $data['body']]); ?>
    <?= Html::submitButton('Изменить событие', ['class' => 'btn btn-success']) ?>
    <?php  ActiveForm::end(); ?>
</div>
