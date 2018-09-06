<?php
require_once('../config/config.php');

session_start();

$urlArray = explode('/', $_SERVER['REQUEST_URI']);

$pageName = 'index';
if($urlArray[1] != ''){
    $pageName = $urlArray[1];
}

$action = ''; //Если нам нужен json
if (!empty($urlArray[2])){
    $action = $urlArray[2];
}

$variables = prepareVariables($pageName, $action);

$isJson = (empty($action)) ? false : true;

echo renderPage($pageName, $variables, $isJson);
