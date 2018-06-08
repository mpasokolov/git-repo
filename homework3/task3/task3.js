//Можно было без классов, но уж очень хотелось что нибудь с классами поделать
window.onload = function () {

  var button = document.getElementById('submit');
  button.addEventListener('click', validate);

};

function validate(ev) {
  var valideName = validateName();
  var validePhone = validatePhone();
  var valideEmail = validateEmail();

  if (!valideName || !validePhone || !valideEmail) {
    ev.preventDefault();
  }
}

function validateName() {
  var name = document.getElementById('name');
  var nameParent = name.parentNode;

  if (!name.value.match(/^[a-zа-яё]+$/i)) {
    if (nameParent.childNodes.length === 2) {
      name.parentNode.appendChild(new Error('Имя должно содержать только буквы и не должно быть пустым').render());
      name.style.borderColor = 'red';
      return false;
    }
  } else if (nameParent.childNodes.length === 3) {
    nameParent.removeChild(nameParent.lastChild);
    name.style.borderColor = '';
    return true;
  }
}

function validateEmail() {
  var email = document.getElementById('email');
  var emailParent = email.parentNode;

  if (!email.value.match(/^[a-z]+[-\.]?[a-z]+@[a-z]{3,20}\.[a-z]{2,3}$/i)) {
    if (emailParent.childNodes.length === 2) {
      email.parentNode.appendChild(new Error('Допустимые форматы для email: mymail@mail.ru, my.mail@mail.ru, ' +
        'my-mail@mail.ru ').render());
      email.style.borderColor = 'red';
      return false;
    }
  } else if (emailParent.childNodes.length === 3) {
    emailParent.removeChild(emailParent.lastChild);
    email.style.borderColor = '';
    return true;
  }
}

function validatePhone() {
  var phone = document.getElementById('phone');
  var phoneParent = phone.parentNode;

  if (!phone.value.match(/^\+\d\(\d{3}\)\d{3}-\d{4}$/)) {
    if (phoneParent.childNodes.length === 2) {
      phone.parentNode.appendChild(new Error('Номер телефона должен быть в формате: +7(000)000-0000').render());
      phone.style.borderColor = 'red';
      return false;
    }
  } else if (phoneParent.childNodes.length === 3) {
    phoneParent.removeChild(phoneParent.lastChild);
    phone.style.borderColor = '';
    return true;
  }
}

function Error(error) {
  this.error = error;
}

Error.prototype.render = function() {
  var text = document.createElement('p');
  text.className = 'error';
  text.textContent = this.error;

  return text;
};
