<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class CreateActivityForm extends ActiveRecord
{
    /**
     * Название активности
     * @var string
     */
    public $name;

    /**
     * День начала события. Хранится в Unix timestamp
     * @var int
     */
    public $start;

    /**
     * День завершения события. Хранится в Unix timestamp
     * @var
     */
    public $end;

    /**
     * ID автора, создавшего события
     * @var int
     */
    public $author;

    /**
     * Описание события
     * @var string
     */
    public $text;

    /**
     * Повторяется событие или нет
     * @var bool
     */
    public $repeat;

    /**
     * Является ли событие блокирующим
     * @var bool
     */
    public $block;

    public $activityFiles = [];

    public static function tableName() {
        return 'activity';
    }


    public function rules()
    {
        return [
            [['name', 'end', 'start'], 'required' , 'message' => 'Пожалуйста, заполните поле!'],
            [['name', 'text'], 'string', 'message' => 'Некорректные данные!'],
            [['start', 'end'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Некорректные данные!'],
            [['activityFiles'], 'file', 'maxFiles' => 0, 'message' => 'Некорректные данные!'],
            ['end', 'activityDateCompare']
        ];
    }

    public function attributeLabels() {
        return [
            'name' => 'Название события',
            'start' => 'Дата начала события',
            'end' => 'Дата окончания события',
            'text' => 'Описание события',
            'repeat' => 'Событие повторяется ежедневно',
            'block' => 'Блокирующее событие',
            'activityFiles' => 'Файлы события'
        ];
    }

    public function upload($date) {
        if ($this -> validate()) {
            foreach ($this -> activityFiles as $file) {
                $dir = \Yii::getAlias('@app/uploads/' . $date . '/');

                if (!file_exists($dir)) {
                    mkdir($dir,  0777, true);
                }

                move_uploaded_file($file -> tempName, $dir . $file -> name);
            }
            return true;
        } else {
            return false;
        }
    }

    public function activityDateCompare() {
        if (strtotime($this->end) <= strtotime($this->start)) {
            $this -> addError('end', 'Дата окончания события должна быть больше или равна дате начала');
        }
    }

    public function insertActivity() {
        $db = \Yii::$app -> db;
        $transaction = $db -> beginTransaction();
        try {
            $db -> createCommand() -> insert('activity', [
                'title' => $this -> name,
                'start_day' => strtotime($this -> start),
                'end_day' => strtotime($this -> end),
                'is_repeat' => $this -> repeat,
                'is_block' => $this -> block,
                'body' => $this -> text,
            ])->execute();

            $id = \Yii::$app->db->getLastInsertID();
            $days = (strtotime($this -> end) - strtotime($this -> start)) / (60 * 60 * 24) + 1;
            \Yii::info('days = '.$days);
            $day = strtotime($this -> start);
            for ($i = 1; $i <= $days; $i++) {
                $date = getDate($day);

                $db->createCommand()->insert('day', [
                    'id_activity' => $id,
                    'id_user' => '1',
                    'date' => $day,
                    'weekend_day' => $date['weekday'] ? true : false,
                ])->execute();

                $day = $day + (60 * 60 * 24);
            }

            $transaction -> commit();
            return true;

        } catch (\Exception $exception) {
            $transaction -> rollBack();
            return false;
        }
    }


}