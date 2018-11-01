<?php
/**
 * @var $model \app\models\CreateActivityForm
 */
?>

<h2>Форма успешно отправлена</h2>
<p><strong>Название события:</strong> <?= $model -> name ?></p>
<p><strong>Дата начала события:</strong> <?= $model -> start ?></p>
<p><strong>Дата начала события:</strong> <?= strtotime($model -> start) ?></p>
<p><strong>Дата окончания события:</strong> <?= $model -> end ?></p>
<p><strong>Блокирующее событие:</strong> <?= $model -> block ?></p>
<p><strong>Событие повторяется ежедневно:</strong> <?= $model -> repeat ?></p>
<p><strong>Текст события:</strong> <?= $model -> text ?></p>





