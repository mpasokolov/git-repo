<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 17/10/2018
 * Time: 20:37
 */

namespace app\components;

use yii\helpers\Html;
use yii\base\Component;

class MessengerComponent extends Component
{
    public $message;

    public function init(){
        parent::init();
        $this->message = "Текст сообщения";
    }

    public function display($userMessage){
        $this->message = $userMessage;

        return Html::encode($this->message);
    }

}