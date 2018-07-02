document.getElementsByClassName('new-user-form__bottom')[0].addEventListener('click', function (ev) {

	ev.preventDefault();

  var form = document.getElementById('new-user-form');
  var shippingAddressBlock = document.getElementById('shipping-address');
  var regForm = document.getElementById('new-user-reg-form');


  if (getRadioChoose()) {

    form.style.display = 'none';
    regForm.style.display = 'flex';

    if (shippingAddressBlock.open) {
      shippingAddressBlock.style.height = '760px';
    }

    document.getElementById('new-user-reg-form__bottom-back').addEventListener('click', function (ev) {
    	ev.preventDefault();

      form.style.display = 'flex';
      regForm.style.display = 'none';

      if (shippingAddressBlock.open) {
        shippingAddressBlock.style.height = '562px';
      }
		})
  }

  document.getElementById('new-user-reg-form__bottom').addEventListener('click', function (ev) {
    ev.preventDefault();

    var validateEmailRez = new Validate('new-user-email', /@/i,
      'Недопустимый формат для email').check();
    var validatePassRez = new Validate('new-user-pass', /(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/,
      'Пароль должен быть не менее 6 символов, а так же содержать хотя бы одну цифру, символ и букву в верхнем' +
			' регистре').check();
    var validatePassConfirm = passwordCompare(document.getElementById('new-user-pass'), document.getElementById('new-user-pass-check'));

    if (!validateEmailRez || !validatePassRez || !validatePassConfirm) {
      return false;
    }

  	var email = document.getElementById('new-user-email').value;
  	var pass = document.getElementById('new-user-pass').value;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost:3000/users/', true);
    xhr.send();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.filter(function (value) { return value.email === email})) {
            if(!document.getElementsByClassName('new-user-reg-form__error_form').length) {
              addErrorMsg('new-user-reg-form', 'Пользователь с таким email уже зарегестрирован');
              document.getElementById('new-user-email').value = '';
            }
          } else {
            var user = JSON.stringify({
              email: email,
              pass: pass
            });
            addNewUser(user);
          }
        }
      }
    };
  });
});

document.getElementById('login').addEventListener('click', function (ev) {

	ev.preventDefault();

  var email = document.getElementById('old-user-email');
  var pass = document.getElementById('old-user-pass');

  var validateEmailRez = new Validate('old-user-email', /.+/i, 'Поле не должно быть пустым').check();
  var validatePassRez = new Validate('old-user-pass', /.+/i, 'Поле не должно быть пустым').check();

  if (!validateEmailRez || !validatePassRez) {
    return false;
  }

  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:3000/users/', true);
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var user = response.filter(function (value) { return (value.email === email.value && value.pass === pass.value)});
        if (!user.length) {
          if(!document.getElementsByClassName('old-user-form__error_form').length) {
            addErrorLoginMsg('old-user-form', 'Неверный e-mail адрес или пароль.');
          }
        } else {
          document.cookie = user[0].email + '=' + user[0].pass;
          toSecondStep();
        }
      }
    }
  };
});

function toSecondStep() {
  var billingInfo = document.getElementById('billing-information');
  var shippingAddressBlock = document.getElementById('shipping-address');

  shippingAddressBlock.open = false;
  billingInfo.open = true;
  shippingAddressBlock.onclick = function (ev1) {
    ev1.preventDefault();
  }
}

function passwordCompare(a, b) {
  var elParent = b.parentNode;
  if (a.value !== b.value) {
    if (elParent.children.length === 2) {
      var text = document.createElement('span');
      text.className = 'new-user-reg-form__error';
      text.textContent = 'Пароли не совпадают';
      b.style.borderColor = 'red';
      elParent.appendChild((text));
      return false
    }
  } else if (elParent.children.length === 3) {
      elParent.removeChild(elParent.lastChild);
      b.style.borderColor = '#eaeaea';
      return true
    }
	return true;
}

function getRadioChoose () {
	var radio = document.getElementsByName('new-user-radio');
  return radio[1].checked;
}

function addNewUser(user) {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'http://localhost:3000/users/', true);
  xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
  xhr.send(user);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 201) {
        addSuccessRegMsg()
      }
    }
  };
}

function addSuccessRegMsg() {
  var success = document.createElement('p');
  success.textContent = 'Поздравляем, вы успешно зарегестрировались!';
  success.className = 'new-user-reg-form__success';
  document.getElementById('new-user').appendChild(success);
  document.getElementById('new-user-reg-form').style.display = 'none';
}

function addErrorLoginMsg(id, error) {
  var form = document.getElementById(id);
  var errorMsg = document.createElement('p');
  errorMsg.className = id + '__error_form';
  errorMsg.textContent = error;
  form.insertBefore(errorMsg, form.children[1]);
}


function Validate(id, regex, error) {
  this.id = id;
  this.regex = regex;
  this.error = error;
}

Validate.prototype.check = function() {
  var el = document.getElementById(this.id);
  var elParent = el.parentNode;
  if (!el.value.match(this.regex)) {
    if (elParent.children.length === 2) {
      elParent.appendChild(this.createMsg());
      el.style.borderColor = 'red';
      return false;
    }
  } else if (elParent.children.length === 3) {
    elParent.removeChild(elParent.lastChild);
    el.style.borderColor = '#eaeaea';
    return true;
  }
  return true;
};

Validate.prototype.createMsg = function() {
  var text = document.createElement('span');
  var formParent = document.getElementById(this.id).parentNode.parentNode;
  text.className = formParent.className + '__error';
  text.textContent = this.error;

  return text;
};


/*
function displayRegForm(el) {
var form = document.createElement('form');
form.className = 'new-user-reg-form';
form.action = '#';
form.method = 'get';

var caption = document.createElement('h3');
caption.className = 'new-user-reg-form__caption';

var emailLabel = document.createElement('label');
emailLabel.className = 'new-user-reg-form__label';

var emailInput = document.createElement('input');
emailInput.className = 'new-user-form__input';
emailInput.type = 'text';
emailInput.name ='new-user-reg-email';

var emailText = document.createElement('span');
emailText.className = 'new-user-reg-form__label-text';
emailText.textContent = 'Email: ';

emailLabel.appendChild(emailInput);
emailLabel.appendChild(emailText);

form.appendChild(caption);
form.appendChild(emailLabel);

var passwordLabel = Object.create(emailLabel);

var passwordInput = document.createElement('input');
passwordInput.className = 'new-user-form__input';
passwordInput.type = 'text';
passwordInput.name = 'new-user-reg-pass';

var passwordText = document.createElement('span');
passwordText.textContent = 'Пароль: ';
passwordText.className = 'new-user-reg-form__label-text';

passwordLabel.appendChild(passwordInput);
passwordLabel.appendChild(passwordText);

form.appendChild(passwordLabel);

return form;
}
*/
