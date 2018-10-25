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

    public function getDates($action, $day, $month) {
        $data = $this -> getStartDay($action, $day, $month);
        $day = $data['monday'];
        $month = $data['month'];
        $year = $data['year'];

        $dates = [];

        for ($i = 1; $i <= 28; $i++) {
            if (checkdate($month, $day, $year)) {
                $monthName = date("F",mktime(0,0,0,$month, $day, $year));
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

    public function getStartDay($action = false, $day = false, $year = false) {
        $date = getdate();

        $newDay = $day ? $day : $date['yday'];
        $newYear = $year ? (int)$year : (int)$date['year'];

        if ($action === 'next') {
            $newDay = $newDay + 28;
            if ($newDay > 365) {
                $newDay = $newDay - 365;
                $newYear = $newYear + 1;
            }
        }

        if ($action === 'prev') {
            $newDay = $newDay - 28;
            if ($newDay < 0) {
                $newDay = $newDay + 365;
                $newYear = $newYear - 1;
            }
            if ($newDay < $date['yday']) {
                $newDay = $date['yday'];
            }
        }

        $newMonth = (int)date("n", mktime(0, 0, 0, 1, $newDay, $newYear));
        $dayMonth = (int)date("j", mktime(0, 0, 0, 1, $newDay, $newYear));

        if (checkdate($newMonth, $dayMonth, $newYear)) {
            $monday = (int)date('d', strtotime('last Monday', strtotime($dayMonth . '.' . $newMonth . '.' . $newYear)));
            $this->day = $monday;
            return ['monday' => $monday, 'day' => $newDay,'month' => $newMonth, 'year' => $newYear];
        } else {
            $monday = (int)date('d', strtotime('last Monday', strtotime($date['mday'] . '.' . $date['mon'] . '.' . $date['year'])));
            return ['monday' => $monday, 'day' => $newDay, 'month' => $date['mon'], 'year' => $date['year']];
        }
    }
}