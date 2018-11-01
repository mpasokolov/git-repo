<?php
/**
 * Created by PhpStorm.
 * UserDefault: pc
 * Date: 21/10/2018
 * Time: 12:10
 */

namespace app\models;


use yii\base\Model;
use yii\db\ActiveRecord;
use app\models\Links;
use app\models\Activity;

class Calendar extends ActiveRecord {

    public static function tableName()
    {
        return 'day';
    }

    public function getDates($action, $day, $month) {
        $data = $this -> getStartDay($action, $day, $month);
        $day = $data['monday'];
        $month = $data['month'];
        $year = $data['year'];
        $dates = [];
        $startDay = null;
        $endDay = null;

        for ($i = 1; $i <= 28; $i++) {
            $id = \Yii::$app->user->id;
            $sql = 'SELECT a.* FROM links l 
                    INNER JOIN activity a ON l.id_activity=a.id 
                    INNER JOIN day d on d.id=l.id_day 
                    WHERE d.date=:days AND l.id_user=:user';
            if (checkdate($month, $day, $year)) {
                $monthName = date("F",mktime(0,0,0,$month, $day, $year));
                $date = ['month' => $monthName, 'day' => $day, 'year' => $year];
                $dayUnix = (string) mktime(0, 0, 0, (int)$month, (int)$date['day'], (int)$date['year']);
                $params = [':user' => $id, ':days' => $dayUnix];
                $db = \Yii::$app -> db;
                $activities = $db->createCommand($sql)
                    ->bindValues($params)
                    ->queryAll();
                //$activities = static::findOne(['date' => $dayUnix])->activities2;
                $date['activities'] = $activities;
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
                $dayUnix = (string) mktime(0, 0, 0, (int)$month, (int)$date['day'], (int)$date['year']);
                $params = [':user' => $id, ':days' => $dayUnix];
                $db = \Yii::$app -> db;
                $activities = $db->createCommand($sql)
                    ->bindValues($params)
                    ->queryAll();
                //$activities = static::findOne(['date' => $dayUnix])->activities2;
                $date['activities'] = $activities;
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
            return ['monday' => $monday, 'day' => $newDay,'month' => $newMonth, 'year' => $newYear];
        } else {
            $monday = (int)date('d', strtotime('last Monday', strtotime($date['mday'] . '.' . $date['mon'] . '.' . $date['year'])));
            return ['monday' => $monday, 'day' => $newDay, 'month' => $date['mon'], 'year' => $date['year']];
        }
    }

    public function getDayActivities() {
        return $this->hasMany(Links::class, ['id_day' => 'id']);
    }

    public function getActivities() {
        return $this->hasMany(Activity::class, ['id' => 'id_activity'])->via('dayActivities');
    }

    public function getActivities2() {
        return $this->hasMany(Activity::class, ['id' => 'id_activity'])->viaTable('Links', ['id_day' => 'id']);
    }
}