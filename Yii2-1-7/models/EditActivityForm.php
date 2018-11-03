<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 29/10/2018
 * Time: 20:47
 */

namespace app\models;


use yii\base\Model;

class EditActivityForm extends Model {
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

    public function rules()
    {
        return [
            [['name'], 'required' , 'message' => 'Пожалуйста, заполните поле!'],
            [['name', 'text'], 'string', 'message' => 'Некорректные данные!'],
            [['start', 'end'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Некорректные данные!'],
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
        ];
    }

    public function activityDateCompare() {
        if (strtotime($this->end) < strtotime($this->start)) {
            $this -> addError('end', 'Дата окончания события должна быть больше или равна дате начала');
        }
    }
}