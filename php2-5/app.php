<?php
require_once 'autoload.php';

try{
    session_set_cookie_params(86400);
    session_start();
    App::init();
}
catch (PDOException $e){
    echo "DB is not available";
    var_dump($e->getTrace());
}
catch (Exception $e){
    echo $e->getMessage();
}

