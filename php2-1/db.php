<?php

  class db
  {
    private $host;
    private $user;
    private $password;
    private $db;
    private $port;

    function __construct($host, $user, $password, $db, $port)
    {
      $this -> host = $host;
      $this -> user = $user;
      $this -> password = $password;
      $this -> db = $db;
      $this -> port = $port;
    }

    function getConnection()
    {
      $mysqli = mysqli_connect($this -> host, $this -> user, $this -> password, $this -> db, $this -> port);
      return $mysqli;
    }
    function executeQuery($sql, $db = null)
    {
      if($db === null){
        $db = $this -> getConnection();
      }

      $result = mysqli_query($db, $sql);
      mysqli_close($db);
      return $result;
    }
    function getAssocResult($sql, $db = null)
    {
      if($db === null){
        $db = $this -> getConnection();
      }

      $result = mysqli_query($db, $sql);
      return $result;
    }
    function getRowResult($sql){
      $array = $this -> getAssocResult($sql);

      if(isset($array[0])){
        $result = $array[0];
      } else {
        $result = [];
      }
      return $result;
    }

  }