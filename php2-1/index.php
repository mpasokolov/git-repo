<?php
  include_once('db.php');
  include_once('good.php');
  include_once('clothes.php');

  echo ('Создаем экземпляр класса Good!<br>');
  $good = new Good('Клавиатура', 499);
  echo ('name = ' . $good -> getName() . '<br>');
  echo ('price = ' . $good -> getPrice()) . '<br>';
  echo 'Добавляем товар в корзину: <br>';
  $result = $good -> addToCart();
  echo $result ? 'Товар успешно добавлен в корзину! <br>' : 'При добавлении товара в корщину произошла ошибка! <br>';

  echo '<hr>';
  echo ('Создаем экземпляр класса Clothers!<br>');

  $hat = new Clothes('Шапка', 1000, 'S');
  echo ('name = ' . $hat -> getName() . '<br>');
  echo ('price = ' . $hat -> getPrice()) . '<br>';
  echo ('size = ' . $hat -> getSize()) . '<br>';
  $result = $hat -> addToCart();
  echo $result ? 'Товар успешно добавлен в корзину! <br>' : 'При добавлении товара в корщину произошла ошибка! <br>';

  echo '<hr>';
  echo 'Задание 5';

  class A {
    public function foo() {
      static $x = 0;
      echo ++$x;
    }
  }
  $a1 = new A();
  $a2 = new A();
  echo '<br>';
  $a1->foo();
  echo '<br>';
  $a2->foo();
  echo '<br>';
  $a1->foo();
  echo '<br>';
  $a2->foo();

  echo '<br>Ответ: это происходит из-за ключевого свойства static. В данном случае после завершения функции переменная не 
  уничтожается и когда мы во второй раз присваеваем ей 0, вместо нуля ей подставляется предыдущее значение.';

  echo '<hr>';
  echo 'Задание 6';

  class B {
    public function foo() {
      static $x = 0;
      echo ++$x;
    }
  }
  class C extends B {
  }
  $a1 = new B();
  $b1 = new C();
  echo '<br>';
  $a1->foo();
  echo '<br>';
  $b1->foo();
  echo '<br>';
  $a1->foo();
  echo '<br>';
  $b1->foo();

  echo '<br>Ответ: Для каждого наследка видимо создается отдельный метод с отдельной static переменной';





