<h1>Активность: <?=$model->title; ?></h1>

<?php if($model->startDay == $model->endDay): ?>
    <p>Событие на <?=date("d.m.Y", $model->startDay)?></p>
<?php else: ?>
    <p>Событие c <?=date("d.m.Y", $model->startDay)?> по <?=date("d.m.Y", $model->endDay)?></p>
<?php endif; ?>

<h3><?=$model->getAttributeLabel('body') ?></h3>
<p><?=$model->body ?></p>

<p>Регулярное:
<?php if($model -> isRepeat === true): ?>
    <span>Да</span>
<?php else: ?>
    <span>Нет</span>
<?php endif; ?>
</p>

<p>Блокирующее:
<?php if($model -> isBlock === true): ?>
    <span>Да</span>
<?php else: ?>
    <span>Нет</span>
<?php endif; ?>
</p>

<?php echo \Yii::$app->messenger->display('Тест компонента display'); ?>



