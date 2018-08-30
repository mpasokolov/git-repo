<?php
  abstract class Good {
    public $price;
    abstract protected function calculateFinalPrice();
    abstract protected function calculateProfit();
  }

  class physicalGoods extends Good {
    public $count;
    function __construct(int $price, int $count) {
      $this -> count = $count;
      $this -> price = $price;
    }
    function calculateFinalPrice() :float {
      return $this -> price;
    }
    function calculateProfit() :float {
      return $this -> price * $this -> count;
    }
  }

  class DigitalGoods extends physicalGoods {
    function __construct(int $price, int $count = 1)
    {
      parent::__construct($price, $count);
    }
    function calculateFinalPrice() :float {
      $this -> price = parent::calculateFinalPrice() / 2;
      return $this -> price;
    }
    function calculateProfit() :float {
      return $this -> price * $this -> count;
    }
  }

  class WeightedGoods extends Good {
    public $weight;
    function __construct(int $price, float $weight) {
      $this -> price = $price;
      $this -> weight = $weight;
    }
    function calculateFinalPrice() :float {
      return $this -> price;
    }
    function calculateProfit() :float {
      return $this -> price * $this -> weight;
    }
  }

  echo "Физический товар\n";
  $toothBrush = new physicalGoods(100, 3);
  echo "price = {$toothBrush -> calculateFinalPrice()}\n";
  echo "profit = {$toothBrush -> calculateProfit()}\n";

  echo "Цифровой товар\n";
  $pdfBook = new DigitalGoods(200);
  echo "price = {$pdfBook -> calculateFinalPrice()}\n";
  echo "profit = {$pdfBook -> calculateProfit()}\n";

  echo "Весовой товар\n";
  $potato = new WeightedGoods(100, 1.5);
  echo "price = {$potato -> calculateFinalPrice()}\n";
  echo "profit = {$potato -> calculateProfit()}\n";