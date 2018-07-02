var xhr = new XMLHttpRequest();
xhr.open('GET', 'http://localhost:3000/cart', true);
xhr.send();

xhr.onreadystatechange = function () {
  if (xhr.readyState === XMLHttpRequest.DONE) {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      buildLittleCart(response);
      var delButtons = document.getElementsByClassName('my-acc-menu__item-del-button');
      for (var i = 0; i < delButtons.length; i ++) {
        delButtons[i].addEventListener('click', function (ev) {
          ev.preventDefault();
          itemDelete(this);
        })
      }
    }
  }
};


function itemDelete(button) {
  var xhr = new XMLHttpRequest();
  xhr.open('DELETE', 'http://localhost:3000/cart/' + button.getAttribute('data-id'), true);
  xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
  xhr.send();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        button.parentNode.parentNode.parentNode.removeChild(button.parentNode.parentNode);
        var cartSumElement = document.getElementById('my-acc-menu-price');
        var price = (+cartSumElement.getAttribute('data-price') - (+button.getAttribute('data-quantity') * +button.getAttribute('data-price')));
        cartSumElement.textContent = '$' + price;
        cartSumElement.setAttribute('data-price', price.toString());
      }
    }
  }
}

function createItem(item) {
  var row = document.createElement('tr');
  row.className = 'my-acc-menu__table-row';

  var imgBlock = document.createElement('td');
  imgBlock.className = 'my-acc-menu__table-cell my-acc-menu__item-img-block';

  var imgLink = document.createElement('a');
  imgLink.href = 'single_page.html';

  var img = document.createElement('img');
  img.className = 'my-acc-menu__item-img';
  img.src = 'img/cart_img' + item.id + '.png';
  img.alt = 'cart-item';

  imgLink.appendChild(img);
  imgBlock.appendChild(imgLink);

  var itemDescription = document.createElement('td');
  itemDescription.className = 'my-acc-menu__table-cell';

  var itemName = document.createElement('h3');
  itemName.className = 'my-acc-menu__item-caption';
  itemName.textContent = item.name;

  var itemRate = document.createElement('p');
  itemRate.className = 'my-acc-menu__item-rate';

  var fullStar = document.createElement('i');
  fullStar.className = 'fa fa-star';
  fullStar.setAttribute('aria-hidden', 'true');

  itemRate.appendChild(fullStar);
  itemRate.appendChild(fullStar.cloneNode(true));
  itemRate.appendChild(fullStar.cloneNode(true));
  itemRate.appendChild(fullStar.cloneNode(true));
  itemRate.appendChild(fullStar.cloneNode(true));

  var itemPrice = document.createElement('p');
  itemPrice.className = 'my-acc-menu__item-price';
  itemPrice.textContent = item.quantity + 'x $' + item.price;

  itemDescription.appendChild(itemName);
  itemDescription.appendChild(itemRate);
  itemDescription.appendChild(itemPrice);

  var delItemBlock = document.createElement('td');
  delItemBlock.className = 'my-acc-menu__table-cell my-acc-menu__item-del';

  var delButton = document.createElement('button');
  delButton.className = 'my-acc-menu__item-del-button';
  delButton.setAttribute('data-id', item.id);
  delButton.setAttribute('data-quantity', item.quantity);
  delButton.setAttribute('data-price', item.price);


  var delIcon = document.createElement('i');
  delIcon.className = 'fa fa-times-circle';
  delIcon.setAttribute('aria-hidden', 'true');

  delButton.appendChild(delIcon);
  delItemBlock.appendChild(delButton);

  row.appendChild(imgBlock);
  row.appendChild(itemDescription);
  row.appendChild(delItemBlock);

  return row
}

function buildLittleCart(items) {
  var itemsTable = document.getElementsByClassName('my-acc-menu__table');

  var cartSum = 0;

  items.forEach(function (item) {
    itemsTable[0].appendChild(createItem(item));
    cartSum = cartSum + item.price * item.quantity;
  });

  var cartSumElement = document.getElementById('my-acc-menu-price');
  cartSumElement.textContent = '$' + cartSum;
  cartSumElement.setAttribute('data-price', cartSum.toString());

}