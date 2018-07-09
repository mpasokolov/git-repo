var getLittleCart = new XMLHttpRequest();
getLittleCart.open('GET', 'http://localhost:3000/cart', true);
getLittleCart.send();

getLittleCart.onreadystatechange = function () {
  if (getLittleCart.readyState === XMLHttpRequest.DONE) {
    if (getLittleCart.status === 200) {
      var response = JSON.parse(getLittleCart.responseText);
      buildLittleCart(response);
      var littleCart = document.getElementsByClassName('my-acc-menu__table');
      littleCart[0].addEventListener('click', function (event) {
        event.preventDefault();
        if (event.target.parentNode.getAttribute('data-id')) {
          itemDelete(event.target.parentNode);
        }
      });
    }
  }
};


function itemDelete(button) {
  var delItemFromLittleCart = new XMLHttpRequest();
  delItemFromLittleCart.open('DELETE', 'http://localhost:3000/cart/' + button.getAttribute('data-id'), true);
  delItemFromLittleCart.setRequestHeader('Content-type', 'application/json; charset=utf-8');
  delItemFromLittleCart.send();
  delItemFromLittleCart.onreadystatechange = function () {
    if (delItemFromLittleCart.readyState === XMLHttpRequest.DONE) {
      if (delItemFromLittleCart.status === 200) {
        button.parentNode.parentNode.parentNode.removeChild(button.parentNode.parentNode);
        var cartSumElement = document.getElementById('my-acc-menu-price');
        var price = (+cartSumElement.getAttribute('data-price') - (+button.getAttribute('data-quantity') * +button.getAttribute('data-price')));
        cartSumElement.textContent = '$' + price;
        cartSumElement.setAttribute('data-price', price.toString());
      }
    }
  }
}

function addToCartItem(data, count, id) {
  if (typeof (data) === 'object') {
    var itemsTable = document.getElementsByClassName('my-acc-menu__table');
    itemsTable[0].appendChild(createItem(data, id))
  } else {
    var el = document.querySelector('button.my-acc-menu__item-del-button[data-id="' + data + '"]');
    var quantity = +el.getAttribute('data-quantity');
    var priceBlock = document.querySelector('span#item-quantity[data-id="' + data + '"]');
    priceBlock.textContent = (quantity + +count).toString();
    el.setAttribute('data-quantity', quantity + +count);
  }
}

function sendGoodToCart(id, size, color, quantity, count) {
  var getGood = new XMLHttpRequest();
  getGood.open('GET', 'http://localhost:3000/goods/' + id, true);
  getGood.send();

  getGood.onreadystatechange = function () {
    if (getGood.readyState === XMLHttpRequest.DONE) {
      if (getGood.status === 200 || getGood.status === 304) {
        var response = JSON.parse(getGood.responseText);
        var good = {
          good_id: response.id,
          name: response.name,
          price: response.price,
          color: color,
          size: size,
          img: response.img,
          about: response.about
        };

        var putItemToCart = new XMLHttpRequest();
        if (!count) {
          good.quantity = quantity;
          putItemToCart.open('POST', 'http://localhost:3000/cart', true);
          putItemToCart.setRequestHeader('Content-type', 'application/json; charset=utf-8');
          putItemToCart.send(JSON.stringify(good));
          putItemToCart.onreadystatechange = function () {
            if (putItemToCart.readyState === XMLHttpRequest.DONE) {
              if (putItemToCart.status === 201) {
                var getGood = new XMLHttpRequest();
                getGood.open('GET', 'http://localhost:3000/cart?good_id=' + good.good_id + '&color=' + good.color + '&size=' + good.size, true);
                getGood.send();
                getGood.onreadystatechange = function () {
                  if (getGood.readyState === XMLHttpRequest.DONE) {
                    if (getGood.status === 200) {
                      var response = JSON.parse(getGood.responseText);
                      var id = response[0].id;
                      addToCartItem(good, 1, id);
                      calcSumLittleCart();
                    }
                  }
                }
              }
            }
          }
        } else {
          good.quantity = +count + +quantity;
          var getGood2 = new XMLHttpRequest();
          getGood2.open('GET', 'http://localhost:3000/cart?good_id=' + good.good_id + '&color=' + good.color + '&size=' + good.size, true);
          getGood2.send();
          getGood2.onreadystatechange = function () {
            if (getGood2.readyState === XMLHttpRequest.DONE) {
              if (getGood2.status === 200) {
                var response = JSON.parse(getGood2.responseText);
                var id = response[0].id;
                putItemToCart.open('PUT', 'http://localhost:3000/cart/' + id, true);
                putItemToCart.setRequestHeader('Content-type', 'application/json; charset=utf-8');
                putItemToCart.send(JSON.stringify(good));
                putItemToCart.onreadystatechange = function () {
                  if (putItemToCart.readyState === XMLHttpRequest.DONE) {
                    if (putItemToCart.status === 200) {
                      addToCartItem(id, quantity);
                      calcSumLittleCart();
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

function createItem(item, id) {
  var row = document.createElement('tr');
  row.className = 'my-acc-menu__table-row';

  var imgBlock = document.createElement('td');
  imgBlock.className = 'my-acc-menu__table-cell my-acc-menu__item-img-block';

  var imgLink = document.createElement('a');
  imgLink.href = 'single_page.html';

  var img = document.createElement('img');
  img.className = 'my-acc-menu__item-img';
  img.src = 'img/' + item.img;
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

  var itemInfo = document.createElement('p');
  itemInfo.className = 'my-acc-menu__item-info';

  var itemCount = document.createElement('span');
  itemCount.textContent =  item.quantity;
  itemCount.id = 'item-quantity';
  if (id) {
    itemCount.setAttribute('data-id', id);
  } else {
    itemCount.setAttribute('data-id', item.id);
  }
  var itemPrice = document.createElement('span');
  itemPrice.textContent = 'x $' + item.price;

  var itemSize = document.createElement('span');
  itemSize.textContent = ' / ' + item.size;

  var itemColor = document.createElement('span');
  itemColor.textContent = ' / ' + item.color;

  itemInfo.appendChild(itemCount);
  itemInfo.appendChild(itemPrice);
  itemInfo.appendChild(itemSize);
  itemInfo.appendChild(itemColor);

  itemDescription.appendChild(itemName);
  itemDescription.appendChild(itemRate);
  itemDescription.appendChild(itemInfo);

  var delItemBlock = document.createElement('td');
  delItemBlock.className = 'my-acc-menu__table-cell my-acc-menu__item-del';

  var delButton = document.createElement('button');
  delButton.className = 'my-acc-menu__item-del-button';
  if (id) {
    delButton.setAttribute('data-id', id);
  } else {
    delButton.setAttribute('data-id', item.id);
  }
  delButton.setAttribute('data-quantity', item.quantity);
  delButton.setAttribute('data-price', item.price);
  delButton.setAttribute('data-size', item.size);
  delButton.setAttribute('data-color', item.color);


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

  items.forEach(function (item) {
    itemsTable[0].appendChild(createItem(item));
  });

  calcSumLittleCart();

}

function calcSumLittleCart() {

  var cartSum = 0;

  var elements = document.querySelectorAll('button.my-acc-menu__item-del-button');
  elements.forEach(function (value) {
    cartSum = cartSum + (+value.getAttribute('data-price') * +value.getAttribute('data-quantity'));
  });

  var cartSumElement = document.getElementById('my-acc-menu-price');
  cartSumElement.textContent = '$' + cartSum;
  cartSumElement.setAttribute('data-price', cartSum.toString());
}

function addSizeEvent(item) {
  item.addEventListener('change', function (ev) {
    ev.preventDefault();

    var newSize = this.value;
    var id = this.getAttribute('data-id');

    var itemColor = document.querySelector('select#item-color[data-id="' + id + '"]');

    while (itemColor.lastChild && itemColor.childElementCount > 0) {
      itemColor.removeChild(itemColor.lastChild);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost:3000/goods/' + id, true);
    xhr.send();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200 || xhr.status === 304) {
          var response = JSON.parse(xhr.responseText);
          for (var size in response.quantity) {
            if (size !== newSize) { continue }
            for (var color in response.quantity[size]) {
              if (response.quantity[size][color] > 0) {
                var colorOption = document.createElement('option');
                colorOption.textContent = color;
                itemColor.appendChild(colorOption);
              }
            }
          }
        }
      }
    };
  });
}

function createGood(item) {
  var itemBlock = document.createElement('div');
  itemBlock.className = 'item';

  var itemLink = document.createElement('a');
  itemLink.className = 'item__link';
  itemLink.href = 'single_page.html#' + item.id;

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

  var itemInfoBlock = document.createElement('div');
  itemInfoBlock.className = 'item__info';

  var itemColor = document.createElement('select');
  itemColor.className = 'item__select item__select_color';
  itemColor.id = 'item-color';
  itemColor.setAttribute('data-id', item.id);


  var itemSize = document.createElement('select');
  itemSize.id = 'item-size';
  itemSize.className = 'item__select item__select_size';
  itemSize.setAttribute('data-id', item.id);
  for (var size in item.quantity) {
    var flag = false;
    var count = 1;
    var sizeOption = document.createElement('option');
    for (var color in item.quantity[size]) {
      if (count > 1) {
        break
      }
      if (+item.quantity[size][color] > 0) {
        var colorOption = document.createElement('option');
        colorOption.text = color;
        itemColor.appendChild(colorOption);
        flag = true;
      }
    }
    if (flag) {
      sizeOption.text = size;
      itemSize.addEventListener('click', function (ev) {
        ev.preventDefault();
      });
      itemColor.addEventListener('click', function (ev) {
        ev.preventDefault();
      });
      itemSize.appendChild(sizeOption);
      count = count + 1;
    }
  }

  addSizeEvent(itemSize);

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

  itemInfoBlock.appendChild(itemPrice);
  itemInfoBlock.appendChild(itemSize);
  itemInfoBlock.appendChild(itemColor);

  descriptionBlock.appendChild(itemName);
  descriptionBlock.appendChild(itemInfoBlock);


  itemLink.appendChild(itemImg);
  itemLink.appendChild(descriptionBlock);

  itemBlock.appendChild(itemLink);
  itemBlock.appendChild(addToCartBlock);

  return itemBlock;
}