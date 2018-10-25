<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 17/10/2018
 * Time: 19:25
 */

namespace app\models;


use yii\base\Model;

class Activity extends Model
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
    public $isRepeat = false;

    /**
     * Является ли событие блокирующим
     * @var bool
     */
    public $isBlock = false;


    public function __construct($title, $startDay, $endDay, $idAuthor, $body)
    {
        parent::__construct();
        $this -> title = $title;
        $this -> startDay = $startDay;
        $this -> endDay = $endDay;
        $this -> idAuthor = $idAuthor;
        $this -> body = $body;
    }

    public function attributeLabels() {
        return [
            'title' => 'Название события',
            'startDay' => 'Дата начала',
            'endDay' => 'Дата завершения',
            'idAuthor' => 'ID автора',
            'body' => 'Описание события'
        ];
    }
}