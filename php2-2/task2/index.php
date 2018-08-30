<?php

trait singleton {

  protected static $_instance;

  public static function getInstance() {
    if (self::$_instance === null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }
}

class DB {

  private static $_HOST = 'localhost';
  private static $_PORT = '8889';
  private static $_USER = 'root';
  private static $_PASSWORD = 'root';
  private static $_NAME_BD = 'lesson8';

  use singleton;

  private  function __construct() {
    echo "<br/><em>Установка соединения с хостом...";
    $this -> connect = mysqli_connect(self::$_HOST, self::$_USER, self::$_PASSWORD, self::$_NAME_BD, self::$_PORT)
      or die("Невозможно установить соединение".mysqli_error($this -> connect));
    $this -> count_sql = 0;
  }
  private function __clone() {}
  private function __wakeup() {}

  public static function query($sql) {

    $obj=self::$_instance;

    if(isset($obj->connect)) {
      $obj->count_sql++;
      $start_time_sql = microtime(true);
      $result = mysqli_query($obj -> connect, $sql) or die("<br/><span style='color:red'>Ошибка в SQL запросе:</span> ".mysqli_error($obj -> connect));
      $time_sql = microtime(true) - $start_time_sql;
      echo "<br/><br/><span style='color:blue'> <span style='color:green'># Запрос номер ".$obj->count_sql.": </span>".$sql."</span> <span style='color:green'>(".round($time_sql,4)." msec )</span>";
      return $result;
    }
    return false;
  }

  public static function fetch_object($object) {
    return mysqli_fetch_object($object);
  }

  public static function fetch_array($object) {
    return mysqli_fetch_array($object);
  }

  public static function insert_id() {
    return mysqli_insert_id(self::$_instance -> connect);
  }

}

DB::getInstance();

$sql = 'SELECT * FROM basket';
$sql2 = 'INSERT INTO basket (id_product, count, price) VALUES (1, 10, 100)';

$basketData = DB::query($sql);
DB::query($sql2);
echo "<br/><span style='color:green'>ID новой записи = </span>" . DB::insert_id();

$resultInArray = DB::fetch_array($basketData);
echo "<br>";
var_dump($resultInArray);

$resultObject = DB::fetch_object($basketData);
echo "<br><br>";
var_dump($resultObject);

