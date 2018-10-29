<?php
/**
 * @var $model \app\models\CreateUserForm
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<?php echo Yii::$app->session->getFlash('success'); ?>
<div class="createUser">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form -> field($model, 'login') -> textInput(); ?>
    <?= $form -> field($model, 'mail') -> textInput(); ?>
    <?= $form -> field($model, 'pass') -> passwordInput(); ?>
    <?= $form -> field($model, 'pass_repeat') -> passwordInput(); ?>

    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end() ?>
</div>
