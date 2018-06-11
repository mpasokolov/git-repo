//Можно было без классов, но уж очень хотелось что нибудь с классами поделать
$(function () {

  $.get("http://localhost:63342/task2/city.json",
    function (response) {
      response.forEach(function (item) {
        $('#city').append($('<option></option>').append(item.city));
      })
    },
    'json');

  document.forms.myForm.addEventListener('submit', function (ev) {

    var validateNameRez = new Validate('name', /^[a-zа-яё]+$/i,
      'Имя должно содержать только буквы и не должно быть пустым').check();
    var validatePhoneRez = new Validate('phone', /^\+\d\(\d{3}\)\d{3}-\d{4}$/,
      'Номер телефона должен быть в формате: +7(000)000-0000').check();
    var validateEmailRez = new Validate('email', /^[a-z]+[-\.]?[a-z]+@[a-z]{3,20}\.[a-z]{2,3}$/i,
      'Допустимые форматы для email: mymail@mail.ru, my.mail@mail.ru, my-mail@mail.ru ').check();

    if (!validateNameRez || !validatePhoneRez || !validateEmailRez) {
      ev.preventDefault();
    }
  });
});

function Validate(id, regex, error) {
  this.id = id;
  this.regex = regex;
  this.error = error;
}

Validate.prototype.check = function() {
  var el = document.getElementById(this.id);
  var elParent = el.parentNode;
  if (!el.value.match(this.regex)) {
    if (elParent.childNodes.length === 2) {
      elParent.appendChild(this.createMsg());
      el.style.borderColor = 'red';
      return false;
    }
  } else if (elParent.childNodes.length === 3) {
    elParent.removeChild(elParent.lastChild);
    el.style.borderColor = '';
    return true;
  }
};

Validate.prototype.createMsg = function() {
  var text = document.createElement('span');
  text.className = 'error';
  text.textContent = this.error;

  return text;
};
