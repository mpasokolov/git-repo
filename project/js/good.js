var id = document.location.hash.replace('#', '');

var getGoods = new XMLHttpRequest();
getGoods.open('GET', 'http://localhost:3000/goods', true);
getGoods.send();

getGoods.onreadystatechange = function () {
  if (getGoods.readyState === XMLHttpRequest.DONE) {
    if (getGoods.status === 200 || getGoods.status === 304) {
      var response = JSON.parse(getGoods.responseText);
      var count = 1;
      response.forEach(function (item) {
        if (count < 5) {
          document.getElementsByClassName('recommend-items__list')[0].appendChild(createGood(item));
        }
        count = count + 1;
      });
      addGoodsEvent();
    }
  }
};

var getGood = new XMLHttpRequest();
getGood.open('GET', 'http://localhost:3000/goods/' + id, true);
getGood.send();

getGood.onreadystatechange = function () {
  if (getGood.readyState === XMLHttpRequest.DONE) {
    if (getGood.status === 200 || getGood.status === 304) {
      var good = JSON.parse(getGood.responseText);
      buildGood(good);
      addGoodSizeEvent();
      addGoodEvent();
    }
  }
};

function addGoodEvent() {
  var button = document.getElementsByClassName('product-description__to-cart');
  button[0].addEventListener('click', function () {
    var item = this;
    var id = item.getAttribute('data-id');
    var itemColorValue = document.getElementById('color').value;
    var itemSizeValue = document.getElementById('size').value;
    var itemQuantityValue = document.getElementById('quantity').value;

    var addButtonEvent = new XMLHttpRequest();
    addButtonEvent.open('GET', 'http://localhost:3000/cart?good_id=' + id + '&size=' + itemSizeValue + '&color=' + itemColorValue, true);
    addButtonEvent.send();

    addButtonEvent.onreadystatechange = function () {
      if (addButtonEvent.readyState === XMLHttpRequest.DONE) {
        if (addButtonEvent.status === 200 || addButtonEvent.status === 304) {
          var response = JSON.parse(addButtonEvent.responseText);
          if (response.length === 1) {
            sendGoodToCart(id, itemSizeValue, itemColorValue, itemQuantityValue, response[0].quantity);
          } else {
            sendGoodToCart(id, itemSizeValue, itemColorValue, itemQuantityValue);
          }
        }
      }
    };
  });
}


function addGoodsEvent() {
  var buttonList = document.getElementsByClassName('item__add-to-cart');
  for (var i = 0; i < buttonList.length; i++) {
    buttonList[i].addEventListener('click', function () {
      var item = this;
      var id = item.getAttribute('data-id');
      var itemColorValue = document.querySelector('select.item__select_color[data-id="' + id + '"]').value;
      var itemSizeValue = document.querySelector('select.item__select_size[data-id="' + id + '"]').value;
      var addButtonEvent = new XMLHttpRequest();
      addButtonEvent.open('GET', 'http://localhost:3000/cart?good_id=' + id + '&size=' + itemSizeValue + '&color=' + itemColorValue, true);
      addButtonEvent.send();

      addButtonEvent.onreadystatechange = function () {
        if (addButtonEvent.readyState === XMLHttpRequest.DONE) {
          if (addButtonEvent.status === 200 || addButtonEvent.status === 304) {
            var response = JSON.parse(addButtonEvent.responseText);
            if (response.length === 1) {
              sendGoodToCart(id, itemSizeValue, itemColorValue, 1, response[0].quantity);
            } else {
              sendGoodToCart(id, itemSizeValue, itemColorValue, 1);
            }
          }
        }
      };
    })
  }
}


function addGoodSizeEvent() {
  var itemSize = document.getElementById('size');
  itemSize.addEventListener('change', function (ev) {
    ev.preventDefault();

    var newSize = this.value;

    var itemColor = document.getElementById('color');

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

function buildGood(good) {
  var goodBlock = document.getElementsByClassName('product-description')[0];

  var collection = document.createElement('h2');
  collection.className = 'product-description__h2';
  collection.textContent = good.collection;

  goodBlock.appendChild(collection);

  var name = document.createElement('h3');
  name.className = 'product-description__h3';
  name.textContent = good.name;

  goodBlock.appendChild(name);

  var about = document.createElement('p');
  about.className = 'product-description__text';
  about.textContent = good.about;

  goodBlock.appendChild(about);

  var madeList = document.createElement('ul');
  madeList.className = 'product-description__made';

  var material = document.createElement('li');
  material.className = 'product-description__made-list';
  material.textContent = 'MATERIAL: ';

  var materialText = document.createElement('span');
  materialText.textContent = good.material;

  material.appendChild(materialText);

  var designer = document.createElement('li');
  designer.className = 'product-description__made-list';
  designer.textContent = 'DESIGNER: ';

  var designerText = document.createElement('span');
  designerText.textContent = good.designer;

  designer.appendChild(designerText);

  madeList.appendChild(material);
  madeList.appendChild(designer);
  goodBlock.appendChild(madeList);

  var price = document.createElement('span');
  price.className = 'product-description__price';
  price.textContent = '$' + good.price;

  goodBlock.appendChild(price);

  var line = document.createElement('hr');
  line.className = 'product-description__hr';

  goodBlock.appendChild(line);

  var form = document.createElement('form');
  form.className = 'item-description-form';

  var itemSizeBlock = document.createElement('div');
  itemSizeBlock.className = 'item-description-form__group';

  var itemColorBlock = document.createElement('div');
  itemSizeBlock.className = 'item-description-form__group';

  var colorLabel = document.createElement('label');
  colorLabel.className = 'item-description-form__label item-description-form__arrow';
  colorLabel.for = 'color';
  colorLabel.textContent = 'CHOOSE COLOR';

  var colorSelect = document.createElement('select');
  colorSelect.className = 'item-description-form__select';
  colorSelect.id = 'color';
  colorSelect.setAttribute('data-id', id);

  var sizeLabel = document.createElement('label');
  sizeLabel.className = 'item-description-form__label item-description-form__arrow';
  sizeLabel.for = 'size';
  sizeLabel.textContent = 'CHOOSE SIZE';

  var sizeSelect = document.createElement('select');
  sizeSelect.className = 'item-description-form__select';
  sizeSelect.id = 'size';
  sizeSelect.setAttribute('data-id', id);

  var flag = false;
  var count = 1;
  for (var size in good.quantity) {
    var sizeOption = document.createElement('option');
    for (var color in good.quantity[size]) {
      if (count > 1) {
        break
      }
      if (good.quantity[size][color] > 0) {
        var colorOption = document.createElement('option');
        colorOption.text = color;
        colorSelect.appendChild(colorOption);
        flag = true;
      }
    }
    if (flag) {
      sizeOption.text = size;
      sizeSelect.appendChild(sizeOption);
      count = count + 1;
    }
  }

  addSizeEvent(sizeSelect);

  itemSizeBlock.appendChild(sizeLabel);
  itemSizeBlock.appendChild(sizeSelect);
  itemColorBlock.appendChild(colorLabel);
  itemColorBlock.appendChild(colorSelect);

  form.appendChild(itemSizeBlock);
  form.appendChild(itemColorBlock);

  var quantityBlock = document.createElement('div');
  quantityBlock.className = 'item-description-form__group';

  var quantityLabel = document.createElement('label');
  quantityLabel.className = 'item-description-form__label';
  quantityLabel.for = 'quantity';
  quantityLabel.textContent = 'QUANTITY';

  var quantity = document.createElement('input');
  quantity.className = 'item-description-form__input';
  quantity.type = 'number';
  quantity.id = 'quantity';
  quantity.value = '1';
  quantity.min = '1';
  quantity.max = '100';

  quantityBlock.appendChild(quantityLabel);
  quantityBlock.appendChild(quantity);

  form.appendChild(quantityBlock);

  var addToCart = document.createElement('button');
  addToCart.className = 'product-description__to-cart';
  addToCart.setAttribute('data-id', id);


  var addToCartIcon = document.createElement('i');
  addToCartIcon.className = 'fa fa-shopping-cart product-description__to-cart-basket';
  addToCartIcon.textContent = ' Add to Cart';

  addToCart.appendChild(addToCartIcon);
  goodBlock.appendChild(form);
  goodBlock.appendChild(addToCart);

}

