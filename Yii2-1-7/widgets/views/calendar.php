<?php

use yii\helpers\Url;
use yii\helpers\Html;

$now = getdate();

?>
<div class="calendar__title">
    <div>пн</div><div>вт</div><div>ср</div><div>чт</div><div>пт</div><div>сб</div><div>вс</div>
</div>
<div class="calendar__flex">
    <?php foreach ($dates as $date): ?>
        <div class="calendar__day day">
            <div class="day__head">
                <?php if ($date['day'] === $now['mday'] && $date['month'] === $now['month'] && $date['year'] === $now['year']): ?>
                    <div class="day__date day__date_now"><?= Html::encode($date['day']) ?> <?=  Html::encode($date['month']) ?></div>
                <?php else: ?>
                    <div class="dat__date"><?=  Html::encode($date['day']) ?> <?=  Html::encode($date['month']) ?></div>
                <?php endif; ?>
                <?php if (count($date['activities']) > 0): ?>
                    <?php foreach ($date['activities'] as $activity): ?>
                        <a href="<?= Url::to(['/activity', 'id' => $activity['id']]) ?>" class="day__event"><?= $activity['title'] ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href="<?= Url::toRoute('activity/create') ?>" class="day__create">+</a>
        </div>
    <?php endforeach; ?>
</div>
