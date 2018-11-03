<?php

use app\widgets\Calendar;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use \app\assets\CalendarAsset;

CalendarAsset::register($this);

?>
<div class="calendar">
    <div class="calendar__head">
        <div class="calendar__month"><?=  Html::encode($dates[1]['month']) . ' ' .  Html::encode($dates[1]['year']) . ' г.'?> </div>
        <?php
            echo Nav::widget([
                'options' => ['class' => 'calendar__nav'],
                'items' => [
                ['label' => '<', 'url' => Url::to(['/site/index', 'day' => $day, 'year' => $year, 'action' => 'prev'])],
                ['label' => '>', 'url' => Url::to(['/site/index', 'day' => $day, 'year' => $year, 'action' => 'next'])],
                ]]);
        ?>
    </div>
    <?php echo Calendar::widget(['model' => $model, 'dates' => $dates]) ?>
</div>