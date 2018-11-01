<?php
/**
 * @var $model \app\models\Activity
 */
use yii\helpers\Url;
?>

<h1>Название активности: <?=$data['title']; ?></h1>

<?php if($data['start_day'] == $data['end_day']): ?>
    <p>Дата события: <?=date("d.m.Y", $data['start_day'])?></p>
<?php else: ?>
    <p>Продолжительность события с <?=date("d.m.Y", $data['start_day'])?> по <?=date("d.m.Y", $data['end_day'])?></p>
<?php endif; ?>

<p>Текст события: <?=$data['body'] ?></p>

<p>Регулярное:
<?php if($data['is_repeat'] === true): ?>
    <span>Да</span>
<?php else: ?>
    <span>Нет</span>
<?php endif; ?>
</p>

<p>Блокирующее:
<?php if($data['is_block'] === true): ?>
    <span>Да</span>
<?php else: ?>
    <span>Нет</span>
<?php endif; ?>
</p>

<p>
    <a href="<?= Url::to(['activity/index', 'id' => Yii::$app->request -> get('id'), 'edit' => true]); ?>">Изменить событие</a>
</p>
<p>
    <a href="<?= Url::toRoute('./site'); ?>">Назад в календарь</a>
</p>

