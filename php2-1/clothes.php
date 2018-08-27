<?php

class Clothes extends good
{
  public $size;

  function __construct($name, $price, $size)
  {
    parent::__construct($name, $price);
    $this -> size = $size;
  }
  function getSize() {
    return $this -> size;
  }
  function addToCart() {
    $name = parent::getName();
    $price = parent::getPrice();
    $db = new db('localhost', 'root', 'root', 'php2-1', '8889');
    $sql = "INSERT INTO cart (name, price, size) VALUES ('$name', '$price', '$this->size')";
    $result = $db -> executeQuery($sql);
    return $result;
  }
}