<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 21/10/2018
 * Time: 12:10
 */

namespace app\models;


use yii\base\Model;

class Calendar extends Model {

    public $day;

    public function getDates($day = false) {
        $date = getdate();

        $this -> day = $day or $date['yday'];

        $month = $date['mon'];
        $day = $date['mday'];
        $year = $date['year'];

        $monthName = date("F",mktime(0,0,0,$month, $day, $year));

        if ($date['weekday'] !== 'Monday') {
            $monday = (int)date('d', strtotime('last Monday', strtotime($day . '.' . $month . '.' . $year)));
            $day = $monday;
        }

        $dates = [];

        for ($i = 1; $i <= 28; $i++) {
            if (checkdate($month, $day, $year)) {
                $date = ['month' => $monthName, 'day' => $day, 'year' => $year];
                array_push($dates, $date);
                $day ++;
            } else {
                if ($month === 12) {
                    $month = 1;
                } else {
                    $month++;
                }
                $day = 1;
                $monthName = date("F",mktime(0,0,0,$month, $day, $year));
                $date = ['month' => $monthName, 'day' => $day, 'year' => $year];
                array_push($dates, $date);
                $day ++;
            }
        }
        return $dates;
    }
}