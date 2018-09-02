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
  private static $_NAME_BD = 'gallery';

  use singleton;

  private  function __construct() {
    $this -> connect = mysqli_connect(self::$_HOST, self::$_USER, self::$_PASSWORD, self::$_NAME_BD, self::$_PORT)
    or die("Невозможно установить соединение".mysqli_error($this -> connect));
  }
  private function __clone() {}
  private function __wakeup() {}

  public static function query($sql) {

    $obj=self::$_instance;

    if(isset($obj->connect)) {
      $result = mysqli_query($obj -> connect, $sql) or die("<br/><span style='color:red'>Ошибка в SQL запросе:</span> ".mysqli_error($obj -> connect));
      return $result;
    }
    return false;
  }

  public static function fetch_object($object) {
    return mysqli_fetch_object($object);
  }

  public static function fetch_array($object) {
    $i = 0;
    $data = [];
    while ($row = mysqli_fetch_assoc($object))
    {
      $data[$i] = $row;
      $i = $i + 1;
    }
    return $data;
  }

  public static function insert_id() {
    return mysqli_insert_id(self::$_instance -> connect);
  }

}
