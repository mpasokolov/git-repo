var xhr = new XMLHttpRequest();
xhr.open('GET', 'http://localhost:3000/goods', true);
xhr.send();

xhr.onreadystatechange = function () {
  if (xhr.readyState === XMLHttpRequest.DONE) {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      var count = 1;
      response.forEach(function (item) {
        if (count < 10) {
          document.getElementById('product-item-list').appendChild(createGood(item));
        }
        count = count + 1;
      });
      addToCartEvent();
      addSizeEvent();
    }
  }
};

function addSizeEvent() {
  var itemSize = document.getElementById('item-size');
  itemSize.addEventListener('change', function (ev) {
    ev.preventDefault();

    var newSize = this.value;

    var itemColor = document.getElementById('item-color');

    while (itemColor.lastChild && itemColor.childElementCount > 0) {
      itemColor.removeChild(itemColor.lastChild);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost:3000/goods/' + itemSize.getAttribute('data-id'), true);
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

function addToCartEvent() {
  var buttonList = document.getElementsByClassName('item__add-to-cart');
  for (var i = 0; i < buttonList.length; i++) {
    buttonList[i].addEventListener('click', function () {
      var item = this;
      var id = item.getAttribute('data-id');
      var itemColorValue = document.querySelector('select.item__select_color[data-id="' + id + '"]').value;
      console.log(itemColorValue);
      var itemSizeValue = document.querySelector('select.item__select_size[data-id="' + id + '"]').value;
      console.log(itemSizeValue);
      var addButtonEvent = new XMLHttpRequest();
      addButtonEvent.open('GET', 'http://localhost:3000/cart?good_id=' + id + '&size=' + itemSizeValue + '&color=' + itemColorValue, true);
      addButtonEvent.send();

      addButtonEvent.onreadystatechange = function () {
        if (addButtonEvent.readyState === XMLHttpRequest.DONE) {
          if (addButtonEvent.status === 200 || addButtonEvent.status === 304) {
            var response = JSON.parse(addButtonEvent.responseText);
            console.log(response);
            if (response.length === 1) {
              sendGoodToCart(id, itemSizeValue, itemColorValue, response[0].quantity);
            } else {
              sendGoodToCart(id, itemSizeValue, itemColorValue);
            }
          }
        }
      };
    })
  }
}

function sendGoodToCart(id, size, color, count) {
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
          console.log('2-1');
          good.quantity = 1;
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
                      addToCartItem(good, id);
                      calcSumLittleCart();
                    }
                  }
                }
              }
            }
          }
        } else {
          good.quantity = count + 1;
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
                      addToCartItem(id);
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
  var flag = false;
  var count = 1;
  for (var size in item.quantity) {
    var sizeOption = document.createElement('option');
    for (var color in item.quantity[size]) {
      if (count > 1) {
        break
      }
      if (item.quantity[size][color] > 0) {
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