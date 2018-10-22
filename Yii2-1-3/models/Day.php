<?php

namespace app\models;

use yii\base\Model;

/**
 * Поле для хранения событий специально делать не стал, пока не вижу в нем смысл, сделал пару методов
 * для получения данных из бд, как мне это видится на первый взгляд
 * @package app\models
 */
class Day extends Model
{
    /**
     * Тип рабочий/выходной
     * @var string
     */
    public $type;

    public function getEvent($eventId) {

    }

    public function getEvents($userId, $dayId) {

    }

    public function addEvent($title, $startDay, $idAuthor, $body, $endDay) {

    }
 }