//Можно было без классов, но уж очень хотелось что нибудь с классами поделать
window.onload = function () {
  var button = document.getElementById('submit');
  button.addEventListener('click', function (ev) {

    //validate name
    var name = document.getElementById('name');
    var nameParent = name.parentNode;

    if (!name.value.match(/^[a-zа-яё]+$/i)) {
      if (nameParent.childNodes.length === 2) {
        name.parentNode.appendChild(new Error('Имя должно содержать только буквы и не должно быть пустым').render());
        name.style.borderColor = 'red';
      }
    } else if (nameParent.childNodes.length === 3) {
      nameParent.removeChild(nameParent.lastChild);
      name.style.borderColor = '';
    }

    //validate phone
    var phone = document.getElementById('phone');
    var phoneParent = phone.parentNode;

    if (!phone.value.match(/^\+\d\(\d{3}\)\d{3}-\d{4}$/)) {
      if (phoneParent.childNodes.length === 2) {
        phone.parentNode.appendChild(new Error('Номер телефона должен быть в формате: +7(000)000-0000').render());
        phone.style.borderColor = 'red';
      }
    } else if (phoneParent.childNodes.length === 3) {
      phoneParent.removeChild(phoneParent.lastChild);
      phone.style.borderColor = '';
    }

    //validate email
    var email = document.getElementById('email');
    var emailParent = email.parentNode;

    if (!email.value.match(/^\w+[-\.]?\w+@\w+\.ru$/)) {
      if (emailParent.childNodes.length === 2) {
        email.parentNode.appendChild(new Error('Допустимые форматы для email: mymail@mail.ru, my.mail@mail.ru, ' +
          'my-mail@mail.ru ').render());
        email.style.borderColor = 'red';

      }
    } else if (emailParent.childNodes.length === 3) {
      emailParent.removeChild(emailParent.lastChild);
      email.style.borderColor = '';
    }

  })
};

function Error(error) {
  this.error = error;
}

Error.prototype.render = function() {
  var text = document.createElement('p');
  text.className = 'error';
  text.textContent = this.error;

  return text;
};
