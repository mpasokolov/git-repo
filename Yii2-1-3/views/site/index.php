<?php
use yii\helpers\Url;
use yii\bootstrap\Nav;
use \app\assets\CalendarAsset;

CalendarAsset::register($this);

$dates = $model -> getDates();
$now = getdate();
?>
<div class="calendar">
    <div class="calendar__head">
        <div class="calendar__month"><?= $now['month'] . ' ' . $now['year'] . ' г.'?> </div>
        <?php
            echo Nav::widget([
                'options' => ['class' => 'calendar__nav'],
                'items' => [
                ['label' => '<'],
                ['label' => '>'],
                ]]);
        ?>
    </div>
    <div class="calendar__title">
        <div>пн</div>
        <div>вт</div>
        <div>ср</div>
        <div>чт</div>
        <div>пт</div>
        <div>сб</div>
        <div>вс</div>
    </div>
    <div class="calendar__flex">
        <?php foreach ($dates as $date): ?>
            <div class="calendar__day day">
                <div class="day__head">
                    <?php if ($date['day'] === $now['mday'] && $date['month'] === $now['month'] && $date['year'] === $now['year']): ?>
                        <div class="day__date day__date_now"><?= $date['day'] ?> <?= $date['month'] ?></div>
                    <?php else: ?>
                        <div class="dat__date"><?= $date['day'] ?> <?= $date['month'] ?></div>
                    <?php endif; ?>
                    <a href="<?= Url::to(['./activity', 'id' => 1]) ?>" class="day__event">Тестовое событие</a>
                </div>
                <a href="<?= Url::toRoute('./activity/create') ?>" class="day__create">+</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>