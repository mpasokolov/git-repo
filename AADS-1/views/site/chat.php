<?php
use yii\web\AssetBundle;

$this->title = 'Домашнее задание №1';
Yii::$app->name = 'Задание №3';
?>

Имя пользователя:<br />
<input id="username" type="text"><button id="btnSetUsername">Введите свое имя</button>

<div id="chat" style="width:400px; height: 250px; overflow: scroll;"></div>

Добавить элемент в очередь:<br />
<input id="message" type="text"><button id="btnSend">Добавить</button><br>
<button id="btnGet">Получить самый старый элемент</button>
<div id="response" style="color:#D00"></div>
