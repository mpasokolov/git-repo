<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 01/11/2018
 * Time: 21:06
 */

namespace app\widgets;


use yii\base\Widget;

class Calendar extends Widget {

    public $model;
    public $dates;
    public $action;
    public $day;
    public $year;

    public function init() {
        parent::init();
    }

    public function run() {
        return $this->render('calendar',
            [
                'model' => $this->model,
                'dates' => $this->dates,
                'action' => $this->action,
                'day' => $this->day,
                'year' => $this->year
            ]);
    }
}