$(function () {
  var data = false;
  var $city = $("#city");

  $city.on("input", function () {

    var value = this.value;
    var $list = $("#list");
    var $submit = $("#submit");

    if (value.length > 2 && !data) {
      loadData(function (response) {
        data = response;
      })
    }

    if (data && value.length > 2) {
      $submit.css("display", "none");
      $list.empty();
      data.forEach(function (item) {
        if (item.city.toLowerCase().match(value.toLowerCase())) {
          var li = $('<li/>');
          li.on("click", function () {
            $list.empty();
            $city[0].value = item.city;
            $submit.css("display", "block");
          });
          $list.append(li.append(item.city));
        }
      });
    } else {
      $list.empty();
      $submit.css("display", "block");
    }

  });

  $("#myForm").on("submit", function (ev) {

    var $dialog = $("#dialog");
    $dialog.dialog({
      title: "Ошибки:",
      appendTo: '#dialog-block',
      autoOpen: false
    });


    var validateNameRez = new Validate("name", /^[a-zа-яё]+$/i,
      "Имя должно содержать только буквы и не должно быть пустым").check();
    var validatePhoneRez = new Validate("phone", /^\+\d\(\d{3}\)\d{3}-\d{4}$/,
      "Номер телефона должен быть в формате: +7(000)000-0000").check();
    var validateEmailRez = new Validate("email", /^[a-z]+[-.]?[a-z]+@[a-z]{3,20}\.[a-z]{2,3}$/i,
      "Допустимые форматы для email: mymail@mail.ru, my.mail@mail.ru, my-mail@mail.ru").check();
    var validateCityRez = new Validate("city", /^[а-я-]+$/i,
      "Поле не должно быть пустым и должно содержать только русские буквы").check();
    var validateDateRez = new Validate("date", /.+/i,
      "Вы не выбрали дату").check();

    if (!validateNameRez || !validatePhoneRez || !validateEmailRez || !validateCityRez || !validateDateRez) {
      $(".fail").effect( "bounce", "slow" );
      $dialog.dialog("open");
      ev.preventDefault();
    } else {
      $dialog.dialog("close");
    }

  });


  $('#date').datepicker({
    dateFormat: "dd-mm-yy",
    dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ],
    monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль",
      "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
    firstDay: 1
  });

});


function loadData(callback) {
  $.get("http://localhost:63342/task1-2/city.json", function (response) {
    callback(response);
  })
}

function Validate(id, regex, error) {
  this.id = id;
  this.regex = regex;
  this.error = error;
}

Validate.prototype.check = function() {
  var id = this.id;
  var $el = $("#"+id);
  var $dialog = $("#dialog");
  var $errorMsg = $(".error"+id);
  if (!$el[0].value.match(this.regex)) {
    if (!$errorMsg.length) {
      $dialog.append(this.createMsg());
      $el.addClass("fail");
    }
    return false;
  } else if ($errorMsg.length) {
    $errorMsg[0].remove();
    $el.removeClass("fail");

  }
  return true;
};

Validate.prototype.createMsg = function() {
  return $("<p/>", {
    class: "error" + this.id + " error",
    text: this.error
  });
};
