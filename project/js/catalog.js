var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:3000/goods', true);
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        response.forEach(function (item) {
          document.getElementById('featured-items__list').appendChild(createGood(item));
        });
        addToCartEvent();
      }
    }
};

function addToCartEvent() {
  var buttonList = document.getElementsByClassName('item__add-to-cart');
  for (var i = 0; i < buttonList.length; i++) {
    buttonList[i].addEventListener('click', function () {
      var item = this;
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'http://localhost:3000/cart/' + this.getAttribute('data-id'), true);
      xhr.send();

      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            sendGoodToCart(item, response.quantity);
            document.getElementsByClassName('my-acc-menu__table')[0].appendChild(createItem(item));
          } else {
            sendGoodToCart(item);
          }
        }
      };
    })
  }
}

function sendGoodToCart(item, count) {
  var good = {
    id: item.getAttribute('data-id'),
    name: item.getAttribute('data-name'),
    price: item.getAttribute('data-price')
  };
  var xhr = new XMLHttpRequest();
  if (!count) {
    good.quantity = 1;
    xhr.open('POST', 'http://localhost:3000/cart', true);
  } else {
    good.quantity = count + 1;
    xhr.open('PUT', 'http://localhost:3000/cart/' + item.getAttribute('data-id'), true);
  }
  xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
  xhr.send(JSON.stringify(good));
}

function createGood(item) {
  var itemBlock = document.createElement('div');
  itemBlock.className = 'item';

  var itemLink = document.createElement('a');
  itemLink.className = 'item__link';
  itemLink.href = 'single_page.html';

  var itemImg = document.createElement('img');
  itemImg.className = 'item__img';
  itemImg.src = 'img/' + item.img;
  itemLink.alt = 'item1';

  var descriptionBlock = document.createElement('div');
  descriptionBlock.className = 'item__description';

  var itemName = document.createElement('p');
  itemName.className = 'item__name';
  itemName.textContent = item.name;

  var itemPrice = document.createElement('span');
  itemPrice.className = 'item__cost';
  itemPrice.textContent = '$' + item.price;

  var addToCartBlock = document.createElement('div');
  addToCartBlock.className = 'item__add-to-cart-block';

  var addToCartButton = document.createElement('button');
  addToCartButton.className = 'item__add-to-cart';
  addToCartButton.innerHTML = 'Add to&nbsp;Cart';
  addToCartButton.setAttribute('data-id', item.id);
  addToCartButton.setAttribute('data-name', item.name);
  addToCartButton.setAttribute('data-price', item.price);

  var addToCart = document.createElement('i');
  addToCart.className = 'fa fa-shopping-cart item__arrow';
  addToCart.setAttribute('aria-hidden', 'true');

  addToCartButton.appendChild(addToCart);
  addToCartBlock.appendChild(addToCartButton);

  descriptionBlock.appendChild(itemName);
  descriptionBlock.appendChild(itemPrice);

  itemLink.appendChild(itemImg);
  itemLink.appendChild(descriptionBlock);

  itemBlock.appendChild(itemLink);
  itemBlock.appendChild(addToCartBlock);

  return itemBlock;
}