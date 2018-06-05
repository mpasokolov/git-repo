/**
 * Класс, объекты которого описывают параметры гамбургера.
 *
 * @constructor
 * @param size        Размер
 * @param stuffing    Начинка
 * @throws {HamburgerException}  При неправильном использовании
 */

function Hamburger(size, stuffing) {
  this.size = size;
  this.stuffing = '';
  this.topping = [];
  if (stuffing === Hamburger.STUFFING_CHEESE
    || stuffing === Hamburger.STUFFING_SALAD
    || stuffing === Hamburger.STUFFING_POTATO) {
    this.stuffing = stuffing;
  } else {
    throw new HamburgerException('Начинка не была добавлена');
  }
}

/* Размеры, виды начинок и добавок */
Hamburger.SIZE_SMALL = 'small';
Hamburger.SIZE_LARGE = 'large';
Hamburger.STUFFING_CHEESE = 'cheese';
Hamburger.STUFFING_SALAD = 'salad';
Hamburger.STUFFING_POTATO = 'potato';
Hamburger.TOPPING_MAYO = 'mayo';
Hamburger.TOPPING_SPICE = 'spice';
/**
 * Добавить добавку к гамбургеру. Можно добавить несколько
 *– при условии, что они разные.
 *
 * @param topping     Тип добавки
 * @throws {HamburgerException}  При неправильном использовании
 */
Hamburger.prototype.addTopping = function (topping) {
  if ((topping === Hamburger.TOPPING_MAYO
    || topping === Hamburger.TOPPING_SPICE) && !this.topping.includes(topping)) {
    this.topping.push(topping);
  } else {
    throw new HamburgerException('Топпинг не был добавлен');
  }
};
/**
 * Убрать добавку – при условии, что она ранее была
 * добавлена.
 *
 * @param topping   Тип добавки
 * @throws {HamburgerException}  При неправильном использовании
 */
Hamburger.prototype.removeTopping = function (topping){
  if (this.topping.includes(topping)) {
    this.topping.splice(this.topping.indexOf(topping), 1)
  } else {
    HamburgerException.message = 'Топпинг не был удален';
  }
};
/**
 * Получить список добавок.
 *
 * @return {Array} Массив добавленных добавок, содержит константы
 *                 Hamburger.TOPPING_*
 */
Hamburger.prototype.getToppings = function () {
  return this.topping;
};
/**
 * Узнать размер гамбургера
 */
Hamburger.prototype.getSize = function () {
  return this.size;
};
/**
 * Узнать начинку гамбургера
 */
Hamburger.prototype.getStuffing = function () {
  return this.stuffing;
};
/**
 * Узнать цену гамбургера
 * @return {Number} Цена в тугриках
 */
Hamburger.prototype.calculatePrice = function () {

  var price = 0;

  var size = document.getElementById('size');

  switch (size.children[size.selectedIndex].value) {
    case Hamburger.SIZE_SMALL:
      price += 50;
      break;
    case Hamburger.SIZE_LARGE:
      price += 100;
      break;
  }

  var stuffing = document.querySelectorAll('.stuffing');

  for (var i = 0; i < stuffing.length; i++) {
    if (stuffing[i].checked) {
      switch (stuffing[i].id) {
        case Hamburger.STUFFING_CHEESE:
          price += 10;
          break;
        case Hamburger.STUFFING_SALAD:
          price += 20;
          break;
        case Hamburger.STUFFING_POTATO:
          price += 15;
          break
      }
    }
  }

  this.topping.forEach(function(item) {
    switch (item) {
      case Hamburger.TOPPING_MAYO:
        price += 20;
        break;
      case Hamburger.TOPPING_SPICE:
        price += 15;
        break;
    }
  });
  return price;
};
/**
 * Узнать калорийность
 * @return {Number} Калорийность в калориях
 */
Hamburger.prototype.calculateCalories = function () {

  var calories = 0;

  var size = document.getElementById('size');

  switch (size.children[size.selectedIndex].value) {
    case Hamburger.SIZE_SMALL:
      calories += 20;
      break;
    case Hamburger.SIZE_LARGE:
      calories += 40;
      break;
  }

  var stuffing = document.querySelectorAll('.stuffing');

  for (var i = 0; i < stuffing.length; i++) {
    if (stuffing[i].checked) {
      switch (stuffing[i].id) {
        case Hamburger.STUFFING_CHEESE:
          calories += 20;
          break;
        case Hamburger.STUFFING_SALAD:
          calories += 5;
          break;
        case Hamburger.STUFFING_POTATO:
          calories += 10;
          break
      }
    }
  }

  this.topping.forEach(function(item) {
    if (item === Hamburger.TOPPING_MAYO) {
      calories += 5;
    }
  });

  return calories;
};
/**
 * Представляет информацию об ошибке в ходе работы с гамбургером.
 * Подробности хранятся в свойстве message.
 * @constructor
 */
function HamburgerException (message) {
  Error.call(this);
  this.message = message;
}

window.onload = function() {
  try {

    var cheeseBurger = new Hamburger('small', 'cheese');

    var topping = document.querySelectorAll('.topping');

    for (var i = 0; i < topping.length; i++) {
      topping[i].addEventListener("change", function () {
        if (this.checked === false) {
          cheeseBurger.removeTopping(this.id);
        } else {
          cheeseBurger.addTopping(this.id);
        }
      });
    }

    var calculate = document.getElementById('calculate');

    calculate.addEventListener('click', function (ev) {

      ev.preventDefault();

      var description = document.getElementById('description');
      description.style.display = 'block';

      var price = document.getElementById('price');
      price.textContent = cheeseBurger.calculatePrice().toString();

      var calories = document.getElementById('calories');
      calories.textContent = cheeseBurger.calculateCalories().toString();

      var checkSize = document.getElementById('check-size');
      checkSize.textContent = cheeseBurger.getSize();

      var checkStuffing = document.getElementById('check-stuffing');
      checkStuffing.textContent = cheeseBurger.getStuffing();

      var checkTopping = document.getElementById('check-topping');
      checkTopping.textContent =
        cheeseBurger.getToppings().length === 0 ? 'none' : cheeseBurger.getToppings().join();

      var checkPrice = document.getElementById('check-price');
      checkPrice.textContent = price.textContent;

      var checkCalories = document.getElementById('check-calories');
      checkCalories.textContent = calories.textContent;
    });
  } catch (e) {
    alert(e.message);
  }
};
