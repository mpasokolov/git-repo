<?php
/**
 * Created by PhpStorm.
 * UserDefault: pc
 * Date: 17/10/2018
 * Time: 19:25
 */

namespace app\models;


use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Activity extends ActiveRecord
{
    public static function tableName() {
        return 'activity';
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ]
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required' , 'message' => 'Пожалуйста, заполните поле!'],
            [['title', 'body'], 'string', 'message' => 'Некорректные данные!'],
            [['start_day', 'end_day'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Некорректные данные!'],
            [['is_block', 'is_repeat'], 'boolean']
        ];
    }

    public function getCalendar() {
        return $this->hasOne(Calendar::class, ['id_activity' => 'id']);
    }

    public function insertActivity() {
        $date = getdate();
        $dayUnix = mktime(0, 0, 0, (int)$date['mon'], (int)$date['mday'], (int)$date['year']);
        $startDay = $this -> start_day ? strtotime($this -> start_day) : $dayUnix;
        $endDay = $this -> start_day ? strtotime($this -> start_day) : $dayUnix;
        $db = \Yii::$app -> db;
        $transaction = $db -> beginTransaction();
        try {
            $db -> createCommand() -> insert('activity', [
                'title' => $this -> title,
                'start_day' => $startDay,
                'end_day' => $endDay,
                'is_repeat' => $this -> is_repeat,
                'is_block' => $this -> is_block,
                'created_at' => time(),
                'body' => $this -> body,
            ])->execute();

            $id = \Yii::$app->db->getLastInsertID();
            $days = (strtotime($this -> end_day) - strtotime($this -> start_day)) / (60 * 60 * 24) + 1;
            \Yii::info('days = '.$days);
            $day = strtotime($this -> start_day);
            $userId = \Yii::$app->user->id;
            for ($i = 1; $i <= $days; $i++) {
                $date = getDate($day);

                $db->createCommand()->upsert('day', [
                    'date' => $day,
                    'weekend_day' => $date['weekday'] ? true : false,
                ])->execute();

                $idDay = $db->createCommand("SELECT id FROM day WHERE date=:day")
                    ->bindValue(':day', $day)
                    ->queryOne();

                $db -> createCommand() -> insert('links', [
                    'id_activity' => $id,
                    'id_user' => $userId,
                    'id_day' => $idDay['id'],
                ])->execute();

                $day = $day + (60 * 60 * 24);
            }

            $transaction -> commit();
            return true;

        } catch (\Exception $exception) {
            //var_dump($exception);
            $transaction -> rollBack();
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this -> start_day = strtotime($this -> start_day);
            $this -> end_day = strtotime($this -> end_day);
            return true;
        }
        return false;
    }

    public function getUsers() {
        return $this->hasMany(User::class, ['id' => 'id_user'])->viaTable('Links', ['id_activity' => 'id']);
    }
}