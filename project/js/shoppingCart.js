buildCart();

window.onload = function () {

  var delButtons = document.getElementsByClassName('product-del-button');

  for (var i = 0; i < delButtons.length; i++) {
    delButtons[i].addEventListener('click', function (ev) {
      ev.preventDefault();
      delItem(this);
    })
  }

  var countFields = document.getElementsByClassName('bought-item__block_number');

  for (var j = 0; j < countFields.length; j++) {

    countFields[j].addEventListener('change', function (ev) {
      ev.preventDefault();
      var element = this;
      var value = this.value;
      if (+value > 0 && parseInt(value) === +value) {
        changeCartItemCount(element.getAttribute('data-id'), +element.value, +element.getAttribute('data-value'));
        element.setAttribute('data-value', element.value);
        var xhr = new XMLHttpRequest();
        xhr.open('PATCH', 'http://localhost:3000/cart/' + this.getAttribute('data-id'), true);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.send(JSON.stringify({quantity:  this.value}));

      } else {
        this.value = oldValue;
      }
    })
  }

  document.getElementById('clear-cart-button').addEventListener('click', function (ev) {
    ev.preventDefault();
    clearCart();
  })
};

function buildCart() {
  var cartBlock = document.getElementById('shopping-list__table');
  var cartPrice = 0;
  var cartPriceTotal = document.getElementById('cart-price-total');
  var cartSubPrice = document.getElementById('cart-sub-price');

  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:3000/cart/', true);
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);

        response.forEach(function (item) {

          var row = document.createElement('tr');
          row.className = 'bought-table__row bought-item';

          var productDetails = document.createElement('td');
          productDetails.className = 'bought-item__block bought-item__product-details';

          var imgLink = document.createElement('a');
          imgLink.className = 'bought-item__link';
          imgLink.href = 'single_page.html';

          var img = document.createElement('img');
          img.className = 'bought-item__img';
          img.src = 'img/cart-item' + item.id + '.jpg';
          img.alt = 'item-img';

          imgLink.appendChild(img);

          var detailsBlock = document.createElement('div');
          detailsBlock.className = 'bought-item__info';

          var productNameLink = document.createElement('a');
          productNameLink.className = 'bought-item__link';
          productNameLink.href = 'single_page.html';

          var productNameText = document.createElement('p');
          productNameText.className = 'bought-item__name';
          productNameText.textContent = item.name;

          productNameLink.appendChild(productNameText);

          var productNameColorBlock = document.createElement('p');
          productNameColorBlock.className = 'bought-item__color';
          productNameColorBlock.textContent = 'Color: ';

          var productNameColorText = document.createElement('span');
          productNameColorText.className = 'bought-item__color_info';
          productNameColorText.textContent = 'Red';

          productNameColorBlock.appendChild(productNameColorText);

          var productSizeColorBlock = document.createElement('p');
          productSizeColorBlock.className = 'bought-item__size';
          productSizeColorBlock.textContent = 'Size: ';

          var productSizeColorText = document.createElement('span');
          productSizeColorText.className = 'bought-item__size_info';
          productSizeColorText.textContent = 'Xll';

          productSizeColorBlock.appendChild(productSizeColorText);

          detailsBlock.appendChild(productNameLink);
          detailsBlock.appendChild(productNameColorBlock);
          detailsBlock.appendChild(productSizeColorBlock);

          productDetails.appendChild(imgLink);
          productDetails.appendChild(detailsBlock);

          row.appendChild(productDetails);

          var productPrice = document.createElement('td');
          productPrice.className = 'bought-item__block';
          productPrice.id = 'price_id' + item.id;
          productPrice.textContent = item.price + '$';

          row.appendChild(productPrice);

          var productCountBlock = document.createElement('td');
          productCountBlock.className = 'bought-item__block';

          var productCountText = document.createElement('input');
          productCountText.className = 'bought-item__block_number';
          productCountText.setAttribute('data-id', item.id);
          productCountText.id = 'count_id' + item.id;
          productCountText.type = 'number';
          productCountText.min = '1';
          productCountText.value = item.quantity;
          productCountText.step = '1';
          productCountText.setAttribute('data-value', item.quantity);

          productCountBlock.appendChild(productCountText);
          row.appendChild(productCountBlock);

          var productDeliveryBlock = document.createElement('td');
          productDeliveryBlock.className = 'bought-item__block';
          productDeliveryBlock.textContent = 'FREE';

          row.appendChild(productDeliveryBlock);

          var productPriceAll = document.createElement('td');
          cartPrice = cartPrice + (+item.price * +item.quantity);
          productPriceAll.className = 'bought-item__block';
          productPriceAll.id = 'price-all_id' + item.id;
          productPriceAll.textContent = +item.price * +item.quantity + '$';

          row.appendChild(productPriceAll);

          var productDelBlock = document.createElement('td');
          productDelBlock.className = 'bought-item__block';

          var productDelButton = document.createElement('button');
          productDelButton.className = 'bought-item__block_action product-del-button';
          productDelButton.setAttribute('data-quantity', item.quantity);
          productDelButton.setAttribute('data-id', item.id);


          var productDelIcon = document.createElement('i');
          productDelIcon.className = 'fa fa-times-circle';
          productDelIcon.setAttribute('aria-hidden', 'true');

          productDelButton.appendChild(productDelIcon);
          productDelBlock.appendChild(productDelButton);

          row.appendChild(productDelBlock);

          cartBlock.appendChild(row);
        });

        cartPriceTotal.textContent = '$' + cartPrice;
        cartPriceTotal.setAttribute('data-price' , cartPrice.toString());
        cartSubPrice.textContent = '$' + cartPrice;
        cartSubPrice.setAttribute('data-price' , cartPrice.toString());


      }
    }
  };
}

function delItem(element) {
  var productBlock = document.getElementById('shopping-list__table');
  var xhrDel = new XMLHttpRequest();
  productBlock.removeChild(element.parentNode.parentNode);
  xhrDel.open('DELETE', 'http://localhost:3000/cart/' + element.getAttribute('data-id'), true);
  xhrDel.setRequestHeader('Content-type', 'application/json; charset=utf-8');
  xhrDel.send();
}

function changeCartItemCount(id, count, oldCount) {
  var priceBlock = document.getElementById('price_id' + id);
  var priceAllBlock = document.getElementById('price-all_id' + id);

  priceAllBlock.textContent = (parseInt(priceBlock.textContent) * count) + '$';

  var cartSubPrice = document.getElementById('cart-sub-price');
  var cartPriceTotal = document.getElementById('cart-price-total');

  var price = +cartSubPrice.getAttribute('data-price') + (parseInt(priceBlock.textContent) * (count - oldCount));

  cartSubPrice.setAttribute('data-price', price.toString());
  cartPriceTotal.setAttribute('data-price', price.toString());

  cartSubPrice.textContent = '$' + price;
  cartPriceTotal.textContent = '$' + price;

  var el = document.querySelector('button[data-id="' + id + '"]');
  el.setAttribute('data-quantity', count.toString());
}

function clearCart() {

  var itemsList = document.getElementById('shopping-list__table');

  var boughtItems = document.getElementsByClassName('bought-item');

  var length = boughtItems.length;

  for (var i = 0; i < length; i++) {
    var xhr = new XMLHttpRequest();
    xhr.open('DELETE', 'http://localhost:3000/cart/' + (i + 1), true);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.send();
  }

  while (itemsList.lastChild && itemsList.childElementCount > 1) {
      itemsList.removeChild(itemsList.lastChild);
  }
}
