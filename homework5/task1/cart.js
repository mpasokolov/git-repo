/*
function makeLoader(url) {
  var items;
  return function() {
    if(items) {
      return items;
    } else {
      $.get(url, {}, function(data) {
        items = data;
      }, 'json');
    }
  }
}

var goodsLoader = makeLoader('http://localhost:3000/goods');
goodsLoader();
*/

function buildGoodsList() {
  $.get('http://localhost:3000/goods', {}, function(goods) {
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
        text: good.name + ' (' + good.quantity + ')'
      }).append($button).appendTo('#goods');
    });
  }, 'json');
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

(function($) {
  $(function() {
    buildCart();
    buildGoodsList();

    $('#goods').on('click', '.buy', function(event) {
      if(+$(this).attr('data-quantity') < 1) {
        alert('Недостаточно товаров');
        return;
      }
      var good = {
        id: $(this).attr('data-id'),
        name: $(this).attr('data-name'),
        price: $(this).attr('data-price')
      };

      var cartGood = $('#cart li[data-id="' + $(this).attr('data-id') + '"]');
      if(cartGood.length) {
        good.quantity = +cartGood.eq(0).attr('data-quantity') + 1;
        
        $.ajax({
          url: 'http://localhost:3000/cart/' + good.id,
          type: 'PUT',
          data: good,
          success: function() {
            buildCart();
            buildGoodsList();
          }
        })
      } else {
        good.quantity = 1;
        $.post('http://localhost:3000/cart', good, function(response) {
          buildCart();
          buildGoodsList();
        }, 'json');
      }
      event.preventDefault();
    });

    $('#cart').on('click', '.delete', function (event) {
      if (+$(this).attr('data-quantity') === 1) {
        $.ajax({
          url: 'http://localhost:3000/cart/' + $(this).attr('data-id').toString(),
          type: 'DELETE',
          success: function() {
            buildCart();
          }
        })
      } else if (+$(this).attr('data-quantity') > 1) {
        var cartGood = $('#cart li[data-id="' + $(this).attr('data-id') + '"]');
        var good = {
          id: $(this).attr('data-id'),
          name: $(this).attr('data-name'),
          price: $(this).attr('data-price')

        };
        good.quantity = +cartGood.eq(0).attr('data-quantity') - 1;
        $.ajax({
          url: 'http://localhost:3000/cart/' + good.id,
          type: 'PUT',
          data: good,
          success: function() {
            buildCart();
          }
        })
      }
      event.preventDefault();
    })

  });
})(jQuery);