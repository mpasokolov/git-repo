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
    public $startDay;

    /**
     * День завершения события. Хранится в Unix timestamp
     * @var
     */
    public $endDay;

    /**
     * ID автора, создавшего события
     * @var int
     */
    public $idAuthor;

    /**
     * Описание события
     * @var string
     */
    public $body;

    /**
     * Повторяется событие или нет
     * @var bool
     */
    public $isRepeat;

    /**
     * Является ли событие блокирующим
     * @var bool
     */
    public $isBlock;

    public $activityFiles = [];


    public function rules()
    {
        return [
            [['title', 'startDay', 'endDay', 'body'], 'required' , 'message' => 'Пожалуйста, заполните поле!'],
            [['title', 'body'], 'string', 'message' => 'Некорректные данные!'],
            [['startDay', 'endDay'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Некорректные данные!'],
            [['activityFiles'], 'file', 'maxFiles' => 0, 'message' => 'Некорректные данные!'],
        ];
    }

    public function attributeLabels() {
        return [
            'title' => 'Название события',
            'startDay' => 'Дата начала события',
            'endDay' => 'Дата окончания события',
            'body' => 'Описание события',
            'isRepeat' => 'Событие повторяется ежедневно',
            'isBlock' => 'Блокирующее событие',
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
}