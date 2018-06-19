(function($) {
  $(function() {
    buildCart();
    buildGoodsList();

    $('#cart-block').droppable({
      drop: function (event, ui) {
        buyGood(ui.draggable[0].firstElementChild);
      }
    });

    $('#goods').on('click', '.buy', function(event) {
      event.preventDefault();
      buyGood(this);
    });

    $('#cart').on('click', '.delete', function (event) {
      event.preventDefault();
      delFromCart(this);
    })

  });
})(jQuery);


function buildGoodsList() {
  $.get('http://localhost:3000/goods', function(goods) {
    $('#goods').empty();
    goods.forEach(function(good) {
      var $button = $('<button/>', {
        text: 'Buy',
        class: 'buy',
        'data-id': good.id,
        'data-price': good.price,
        'data-name': good.name,
        'data-quantity': good.quantity
      });
      $('<li/>', {
        text: good.name + ' (' + good.quantity + ')',
        class: 'good'
      }).draggable({
        revert: true,
        appendTo: '#cart',
      }).append($button).appendTo('#goods');
    });
  }, 'json');
}

function reduceCountAndBuildGoods(id, count) {
  $.ajax({
    url: 'http://localhost:3000/goods/' + id,
    type: 'PATCH',
    data: {'quantity': count - 1},
    success: function() {
      buildGoodsList()
    }
  })
}

function buyGood(item) {
  var count = +$(item).attr('data-quantity');

  if(+$(item).attr('data-quantity') < 1) {
    alert('Недостаточно товара');
    return;
  }

  var good = {
    id: $(item).attr('data-id'),
    name: $(item).attr('data-name'),
    price: $(item).attr('data-price')
  };

  var cartGood = $('#cart li[data-id="' + $(item).attr('data-id') + '"]');
  if(cartGood.length) {
    good.quantity = +cartGood.eq(0).attr('data-quantity') + 1;
    $.ajax({
      url: 'http://localhost:3000/cart/' + good.id,
      type: 'PUT',
      data: good,
      success: function() {
        reduceCountAndBuildGoods(good.id, count);
        buildCart();
      }
    })
  } else {
    good.quantity = 1;
    $.post('http://localhost:3000/cart', good, function() {
      reduceCountAndBuildGoods(good.id, count);
      buildCart();
    }, 'json');
  }
}

function delFromCart(item) {
  if (+$(item).attr('data-quantity') === 1) {
    $.ajax({
      url: 'http://localhost:3000/cart/' + $(item).attr('data-id'),
      type: 'DELETE',
      success: function() {
        buildCart();
        addToGoods(item);
      }
    })
  } else if (+$(item).attr('data-quantity') > 1) {
    var cartGood = $('#cart li[data-id="' + $(item).attr('data-id') + '"]');
    var quantity = +cartGood.eq(0).attr('data-quantity') - 1;
    $.ajax({
      url: 'http://localhost:3000/cart/' + $(item).attr('data-id'),
      type: 'PATCH',
      data: {'quantity': quantity},
      success: function() {
        buildCart();
        buildGoodsList();
        addToGoods(item);
      }
    })
  }
}

function addToGoods(item) {
  $.get('http://localhost:3000/goods/' + $(item).attr('data-id'), function(response) {
    var inStock = response.quantity;
    $.ajax({
      url: 'http://localhost:3000/goods/' + $(item).attr('data-id'),
      type: 'PATCH',
      data: {'quantity': +inStock + 1},
      success: function () {
        buildCart();
        buildGoodsList();
      }
    })
  }, 'json')
}

function buildCart() {
  $.get('http://localhost:3000/cart', {}, function(items) {
    var cart = $('#cart');
    cart.empty();
    var $ul = $('<ul/>', {
      id: 'cart-list',
      class: 'cart-list'
    });
    var total = 0;
    items.forEach(function(item) {
      total += +item.price * +item.quantity;
      var $li = $('<li/>', {
        text: item.name + ': ' + item.price + ' rub' + '(' +item.quantity + ')',
        'data-id': item.id,
        'data-quantity': item.quantity
      });
      var $delButton = $('<button/>', {
        text: 'Delete',
        class: 'delete',
        'data-id': item.id,
        'data-price': item.price,
        'data-name': item.name,
        'data-quantity': item.quantity
      });
      $li.append($delButton);
      $ul.append($li);
    });
    cart.append($ul);
    cart.append('Total: ' + total + ' rub.')
  }, 'json');
}