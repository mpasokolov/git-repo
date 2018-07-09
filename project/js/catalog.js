var page = document.location.hash.replace('#', '');
var filter = {
  sizeArr: {
    XXS: [],
    XS: [],
    S: [],
    M: [],
    L: [],
    XL: [],
    XXL: []
  },
  sort: 'name',
  goods_cont: 9,
  max_price: Infinity,
  min_price: 0
};

getMaxAndMinPriceToRange();
createSizeFilterEvent();
createNameAndPriceSortEvent();
createGodsCountSortEvent();
createRangePriceEvent();
buildGoodList(page, createFilterStr());
buildPagination();

function getMaxAndMinPriceToRange() {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:3000/goods?_sort=price&_order=desc', true);
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200 || xhr.status === 304) {
        var response = JSON.parse(xhr.responseText);
        document.getElementById('price-range-output-max').textContent = response[0].price;
        document.getElementById('range').max = response[0].price;
        document.getElementById('range').value = response[0].price;

        document.getElementById('price-range-output-min').textContent = response[response.length - 1].price;
        document.getElementById('range').min = response[response.length - 1].price;
      }
    }
  }
}

function createRangePriceEvent() {
  var priceRange = document.getElementById('range');
  priceRange.addEventListener('change', function (ev) {
    ev.preventDefault();
    var page = document.location.hash.replace('#', '');
    var price = document.getElementById('price-range-output-max');
    price.textContent = priceRange.value;
    filter.max_price = priceRange.value;
    emptyItem(document.getElementById('product-item-list'));
    buildGoodList(page, createFilterStr());
    addToCartEvent();
    buildPagination();
  })
}

function buildPagination() {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:3000/goods?' + createFilterStr() + filter.goods_cont +'&price_lte=' + filter.max_price, true);
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200 || xhr.status === 304) {
        var response = JSON.parse(xhr.responseText);
        var number = response.length + 1;
        var pageCount = Math.ceil(number / filter.goods_cont);
        createPaginationList(pageCount, page);
        paginationArrow(page, pageCount);
      }
    }
  };
}

function createGodsCountSortEvent() {
  var selectSotr = document.getElementById('sort-by');
  document.getElementById('sort-count-block-button').addEventListener('click', function (ev) {
    ev.preventDefault();
    filter.goods_cont = selectSotr.value;
    emptyItem(document.getElementById('product-item-list'));
    buildGoodList(page, createFilterStr());
    addToCartEvent();
    buildPagination();
  })

}

function createNameAndPriceSortEvent() {
  var selectSort = document.getElementById('show');
  document.getElementById('sort-block-button').addEventListener('click', function (ev) {
    ev.preventDefault();
    if (selectSort.value === 'Name') {
      filter.sort = 'name';
    } else {
      filter.sort = 'price';
    }
    var page = document.location.hash.replace('#', '');
    emptyItem(document.getElementById('product-item-list'));
    buildGoodList(page, createFilterStr());
    addToCartEvent();
  })
}

function createSizeFilterEvent() {
  var sizeInputs = document.getElementsByClassName('size__input');

  for (var i = 0; i < sizeInputs.length; i++) {
    sizeInputs[i].addEventListener('change', function (ev) {
      ev.preventDefault();
      if (this.checked) {
        var filterSize = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://localhost:3000/goods', true);
        xhr.send();

        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200 || xhr.status === 304) {
              var response = JSON.parse(xhr.responseText);
              emptyItem(document.getElementById('product-item-list'));
              response.forEach(function (good) {
                for (var size in good.quantity) {
                  if (!good.quantity.hasOwnProperty(size)) continue;
                  for (var color in good.quantity[size]) {
                    if (!good.quantity[size].hasOwnProperty(color)) continue;
                    if (filterSize.toUpperCase() === size && good.quantity[size][color] > 0) {
                      filter.sizeArr[size].push(good.id);
                    }
                  }
                }
              });
              var page = document.location.hash.replace('#', '');
              buildGoodList(page, createFilterStr());
              addToCartEvent();
            }
          }
        };
      } else {
        var page = document.location.hash.replace('#', '');
        filter.sizeArr[this.value.toUpperCase()] = [];
        buildGoodList(page, createFilterStr());
        addToCartEvent();
      }
    });
  }
}

function createFilterStr() {
  var str = '';
  var arr = filter.sizeArr;
  for (var val in arr) {
    arr[val].forEach(function (id) {
      str = str + 'id=' + id + '&';
    });
  }
  str = str + '_sort=' + filter.sort + '&_order=desc&';
  return str;
}


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
      var itemSizeValue = document.querySelector('select.item__select_size[data-id="' + id + '"]').value;
      var addButtonEvent = new XMLHttpRequest();
      addButtonEvent.open('GET', 'http://localhost:3000/cart?good_id=' + id + '&size=' + itemSizeValue + '&color=' + itemColorValue, true);
      addButtonEvent.send();

      addButtonEvent.onreadystatechange = function () {
        if (addButtonEvent.readyState === XMLHttpRequest.DONE) {
          if (addButtonEvent.status === 200 || addButtonEvent.status === 304) {
            var response = JSON.parse(addButtonEvent.responseText);
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

function paginationArrow(page, pageCount) {
  var forwardPoint = document.getElementById('pagination-forward');
  var backPoint = document.getElementById('pagination-back');

  var page = document.location.hash.replace('#', '');

  if (+pageCount === 1 && +page === 1) {
    forwardPoint.style.display = 'none';
    backPoint.style.display = 'none';
  } else if (+page === +pageCount) {
    forwardPoint.style.display = 'none';
    backPoint.style.display = 'block';
  } else if (page === '1') {
    backPoint.style.display = 'none';
    forwardPoint.style.display = 'block';
  } else {
    forwardPoint.style.display = 'block';
    backPoint.style.display = 'block';
  }

}

function createPaginationList(pageCount) {
  var pagination = document.getElementsByClassName('product-item-list-nav__list');

  emptyItem(pagination[0]);

  var backPoint = document.createElement('li');
  backPoint.className = 'product-item-list-nav__list-point';
  backPoint.id = 'pagination-back';

  var backLink = document.createElement('a');
  backLink.className = 'product-item-list-nav__link';
  backLink.innerHTML = '&lt;';

  backLink.addEventListener('click', function (ev) {
    ev.preventDefault();
    var oldPge = +document.location.hash.replace('#', '');
    var page = oldPge - 1;
    history.pushState(null, null, 'product.html#' + page);
    buildGoodList(page, createFilterStr());
    paginationArrow(page, pageCount);
  });

  backPoint.appendChild(backLink);
  pagination[0].appendChild(backPoint);

  for (var i = 1; i <= pageCount; i++) {
    var paginationPoint = document.createElement('li');
    paginationPoint.className = 'product-item-list-nav__list-point';

    var paginationLink = document.createElement('a');
    paginationLink.className = 'product-item-list-nav__link';
    paginationLink.textContent = i.toString();
    paginationLink.setAttribute('data-page', i.toString());


    paginationLink.addEventListener('click', function (ev) {
      ev.preventDefault();
      var oldPage = +document.location.hash.replace('#', '');
      var pageLink = document.querySelector('a.product-item-list-nav__link[data-page="' + oldPage + '"]');
      pageLink.classList.remove('product-item-list-nav__list-point_active-page');
      this.classList.add('product-item-list-nav__list-point_active-page');
      history.pushState(null, null, 'product.html#' + this.getAttribute('data-page'));
      buildGoodList(this.getAttribute('data-page'), createFilterStr());
      paginationArrow(this.getAttribute('data-page'), pageCount);
    });

    paginationPoint.appendChild(paginationLink);
    pagination[0].appendChild(paginationPoint);

  }

  var page = +document.location.hash.replace('#', '');
  var pageLink = document.querySelector('a.product-item-list-nav__link[data-page="' + page + '"]');
  pageLink.classList.add('product-item-list-nav__list-point_active-page');


  var forwardPoint = document.createElement('li');
  forwardPoint.className = 'product-item-list-nav__list-point';
  forwardPoint.id = 'pagination-forward';


  var forwardLink = document.createElement('a');
  forwardLink.className = 'product-item-list-nav__link';
  forwardLink.innerHTML = '&gt;';

  forwardLink.addEventListener('click', function (ev) {
    ev.preventDefault();
    var page = +document.location.hash.replace('#', '') + 1;
    history.pushState(null, null, 'product.html#' + page);
    buildGoodList(page, createFilterStr());
    paginationArrow(page, pageCount);
  });

  forwardPoint.appendChild(forwardLink);
  pagination[0].appendChild(forwardPoint);

}

function emptyItem(item) {
  while (item.lastChild && item.childElementCount > 0) {
    item.removeChild(item.lastChild);
  }
}

function buildGoodList(page, filterString) {
  var productBlock = document.getElementById('product-item-list');
  emptyItem(productBlock);
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:3000/goods?' + filterString +'_page=' + page + '&_limit=' + filter.goods_cont +'&price_lte=' + filter.max_price, true);
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200 || xhr.status === 304) {
        var response = JSON.parse(xhr.responseText);
        response.forEach(function (item) {
          productBlock.appendChild(createGood(item));
        });
        buildPagination();
        addToCartEvent();
        addSizeEvent();
      }
    }
  };
}

