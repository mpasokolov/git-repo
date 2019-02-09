<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>

<?php if(Yii::$app->session->hasFlash('fail')): ?>
    <div class="alert alert-danger" role="alert">
        <?= Yii::$app->session->getFlash('fail') ?>
    </div>
<?php endif; ?>

<?php if(Yii::$app->session->hasFlash('already-sent')): ?>
    <div class="alert alert-danger" role="alert">
        <?= Yii::$app->session->getFlash('already-sent') ?>
    </div>
<?php endif; ?>

<?= GridView::widget([
    'dataProvider' => $teamsDataProvider,
    'summary' => '<h3>Состав команды</h3>',
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'username',
        'email',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'visibleButtons' => [
                'delete' => \Yii::$app -> user -> can('teamLeadPermission'),
            ],
            'urlCreator' => function ($action, $model, $key, $index) use ($team){
                return Url::to(['team/'. $action, 'id' => $model -> id, 'team' => $team]);
            },
        ],

    ]
]) ?>

<?php if (\Yii::$app -> user -> can('teamLeadPermission')): ?>

    <a class="btn btn-danger" href="<?= Url::to(['team/delete-all','team' => $team]); ?>">Расформировать команду</a><br><br>

    <div class="send-invite col-sm-3">
        <?php $form = ActiveForm::begin();

            $users = ArrayHelper::map($users, 'id', 'username');
            $params = [
                'prompt' => 'Выберите пользователя'
            ]
        ?>

        <?= $form -> field($invites, 'id_to') -> dropDownList($users, $params); ?>
        <?= $form -> field($invites, 'id_team') -> hiddenInput(['value' => $team]) -> label(false); ?>

        <?= Html::submitButton('Отправить приглашение ', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end() ?>
    </div>
<?php endif; ?>
