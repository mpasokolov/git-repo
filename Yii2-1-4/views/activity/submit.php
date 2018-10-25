<?php
/**
 * @var $model \app\models\CreateActivityForm
 */
?>

<h2>Форма успешно отправлена</h2>
<p><strong>Название события:</strong> <?= $model -> title ?></p>
<p><strong>Дата начала события:</strong> <?= $model -> startDay ?></p>
<p><strong>Дата окончания события:</strong> <?= $model -> endDay ?></p>
<p><strong>Блокирующее событие:</strong> <?= $model -> isBlock ?></p>
<p><strong>Событие повторяется ежедневно:</strong> <?= $model -> isRepeat ?></p>
<p><strong>Текст события:</strong> <?= $model -> body ?></p>





