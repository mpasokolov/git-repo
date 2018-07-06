var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:3000/goods', true);
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var count = 1;
        response.forEach(function (item) {
          if (count < 9) {
            document.getElementById('featured-items__list').appendChild(createGood(item));
          }
          count = count + 1;
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