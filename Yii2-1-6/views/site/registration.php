<?php
/**
 * @var $model \app\models\CreateUserForm
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="createUser">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form -> field($model, 'username') -> textInput(); ?>
    <?= $form -> field($model, 'email') -> textInput(); ?>
    <?= $form -> field($model, 'password') -> passwordInput(); ?>
    <?= $form -> field($model, 'password_repeat') -> passwordInput(); ?>

    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end() ?>
</div>
