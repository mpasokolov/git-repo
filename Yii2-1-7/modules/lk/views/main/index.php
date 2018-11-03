<?php
use yii\helpers\Html;
use yii\mail\BaseMailer;

?>
<h2>Вы вошли как <?= $data->username ?></h2>
<p>Ваш email: <?= $data->email ?></p>
<p><?= Html::a('Посмотреть свои активности', '@web/lk/activity'); ?></p>
<p><?= Html::a('Редактировать данные', ['@web/lk/main/update', 'id' => $data->id]); ?></p>