<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class CreateActivityForm extends Model
{
    /**
     * Название активности
     * @var string
     */
    public $title;

    /**
     * День начала события. Хранится в Unix timestamp
     * @var int
     */
    public $start_day;

    /**
     * День завершения события. Хранится в Unix timestamp
     * @var
     */
    public $end_day;

    /**
     * Описание события
     * @var string
     */
    public $body;

    /**
     * Повторяется событие или нет
     * @var bool
     */
    public $is_repeat;

    /**
     * Является ли событие блокирующим
     * @var bool
     */
    public $is_block;

    public $activityFiles = [];

    public static function tableName() {
        return 'activity';
    }


    public function rules()
    {
        return [
            [['title'], 'required' , 'message' => 'Пожалуйста, заполните поле!'],
            [['title', 'body'], 'string', 'message' => 'Некорректные данные!'],
            [['start_day', 'end_day'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Некорректные данные!'],
            [['activityFiles'], 'file', 'maxFiles' => 0, 'message' => 'Некорректные данные!'],
            ['end_day', 'activityDateCompare'],
            [['is_block', 'is_repeat'], 'boolean']
        ];
    }

    public function attributeLabels() {
        return [
            'title' => 'Название события',
            'start_day' => 'Дата начала события',
            'end_day' => 'Дата окончания события',
            'body' => 'Описание события',
            'is_repeat' => 'Событие повторяется ежедневно',
            'is_block' => 'Блокирующее событие',
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
        if (strtotime($this->end_day) < strtotime($this->start_day)) {
            $this -> addError('end_day', 'Дата окончания события должна быть больше или равна дате начала');
        }
    }

    public function createActivity()
    {

        if (!$this->validate()) {
            return null;
        }

        $activity = new Activity();
        $activity->title = $this->title;
        $activity->start_day = $this->start_day;
        $activity->end_day = $this->end_day;
        $activity->is_repeat = $this->is_repeat;
        $activity->is_block = $this->is_block;
        $activity->body = $this->body;
        return $activity->insertActivity() ? $activity : null;
    }
}