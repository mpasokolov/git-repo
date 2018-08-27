<?php

class Good
{
  private $name;
  private $price;

  function __construct($name, $price) {
    $this->name = $name;
    $this->price = $price;
  }
  function getName() {
    return $this -> name;
  }
  function getPrice() {
    return $this -> price;
  }
  function addToCart() {
    $db = new db('localhost', 'root', 'root', 'php2-1', '8889');
    $sql = "INSERT INTO cart (name, price) VALUES ('$this->name', '$this->price')";
    $result = $db -> executeQuery($sql);
    return $result;
  }
}
