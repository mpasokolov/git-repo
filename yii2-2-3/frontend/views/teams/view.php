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

<?php $team -> parentTeam
    ? $message = \yii\helpers\Html::a($team -> parentTeam -> name, Url::to(['view', 'id' => $team -> parent_id]))
    : $message = 'Не задан'
?>
<h3>Родительский проект: <?= $message ?></h3>

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
            'urlCreator' => function ($action, $model, $key, $index) use ($teamId){
                return Url::to(['teams/'. $action, 'id' => $model -> id, 'team' => $teamId]);
            },
        ],

    ]
]) ?>

<?php if (\Yii::$app -> user -> can('teamLeadPermission')): ?>

    <a class="btn btn-danger" href="<?= Url::to(['teams/delete-all','team' => $team]); ?>">Расформировать команду</a><br><br>

    <div class="send-invite col-sm-3">
        <?php $form = ActiveForm::begin();

            $users = ArrayHelper::map($users, 'id', 'username');
            $params = [
                'prompt' => 'Выберите пользователя'
            ]
        ?>

        <?= $form -> field($invites, 'id_to') -> dropDownList($users, $params); ?>
        <?= $form -> field($invites, 'id_team') -> hiddenInput(['value' => $teamId]) -> label(false); ?>

        <?= Html::submitButton('Отправить приглашение ', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end() ?>
    </div>

    <div class="task-list">
        <?= GridView::widget([
            'dataProvider' => $tasksDataProvider,
            'filterModel' => $tasksSearchModel,
            'summary' => '<h3>Список задач команды</h3>',
            'columns' => [
                'name:text:Название',
                [
                    'attribute' => 'id_admin',
                    'label' => 'Автор',
                    'value' => 'admins.username',

                ],
                [
                    'attribute' => 'id_user',
                    'label' => 'Исполнитель',
                    'value' => 'users.username',

                ],
                [
                    'attribute' => 'deadline',
                    'format' => ['date', 'php:Y-m-d'],

                ],
                [
                    'attribute' => 'finish',
                    'value' => function($model) {
                        return $model -> finish === 1 ? 'Выполнена' : 'Не выполнена';
                    },
                    'filter' => ['1' => 'Выполнена', '0' => 'Не выполнена'],
                    'label' => 'Статус',
                ],
                [
                    'attribute' => 'finish_time',
                    'label' => 'Дата выполнения',
                    'value' => function($model) {
                        return $model -> finish ? date('Y-m-d', $model -> finish_time) : 'Не завершена';
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата создания',
                    'format' => ['date', 'php:Y-m-d'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return Url::to(['tasks/'. $action, 'id' => $model->id]);
                    },
                    'template' => '{view} {update} {delete} {finish}',
                    'buttons' => [
                        'finish' => function ($url, $model, $key) {
                            if (!$model -> finish) {
                                return Html::a('', $url, ['class' => 'glyphicon glyphicon-ok']);
                            }
                        },
                        'update' => function ($url, $model, $key) {
                            if (!$model -> finish) {
                                return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil']);
                            }
                        },
                        'delete' => function ($url, $model, $key) {
                            if (!$model -> finish) {
                                return Html::a('', $url, ['class' => 'glyphicon glyphicon-trash']);
                            }
                        },
                    ]
                ]
            ]
        ]) ?>
    </div>

<?php endif; ?>
