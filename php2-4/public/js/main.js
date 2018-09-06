$(document).ready(function () {

  function checkDateEntrance(date, period) {
      let periods = period.split(',');
      return date >= new Date(periods[0]) && date <= new Date(periods[1]);
  }

  let basket = new Basket('basket');
  basket.render($('#basket_wrapper'));

  $.ajax({
    url: '/goods/getGoods',
    type: 'GET',
    dataType: 'json',
    context: this,
    error: function (err) {
      console.log('Ошибка', err);
    },
    success: function (data) {
      if (data.result) {
        let $goods = $('#goods');
        data.goods.forEach(function (item) {
            if (item.discont) {
                if (checkDateEntrance(new Date(), item.discont_start + ',' + item.discont_end)) {
                    let good = new Good(item.id, item.name, item.price, item.discont / 100);
                    good.render($goods);
                } else {
                    let good = new Good(item.id, item.name, item.price);
                    good.render($goods);
                }
            } else {
                let good = new Good(item.id, item.name, item.price);
                good.render($goods);
            }
        });
        //Добавление товара в корзину
        $goods.on('click', '.buygood', function () {
          let idProduct = parseInt($(this).attr('data-id'));
          basket.addProductAjax(idProduct);
        });
        $goods.on('click', '.delgood', function () {
          let idProduct = parseInt($(this).attr('data-id'));
          basket.dellProductAjax(idProduct);
        });
      } else {
        console.log('Не удалось получить товары. Повторите попытку позже.');
      }
    }
  });

  basket.ajaxGetCartItems();

  $('#addGood').on('click', function (ev) {
    ev.preventDefault();
    let name = $('#newGoodName').val();
    let price = $('#newGoodPrice').val();
    let category = $('#newGoodCategory').val();
    let data = {
      "name": name,
      "price": price,
      "category": category
    };
    $.ajax({
      url: '/goods/addNewGood',
      type: 'POST',
      dataType: 'json',
      context: this,
      data,
      error: function (err) {
        console.log('Ошибка', err);
      },
      success: function (data) {
        if (data.result) {
          let $good = new Good(data.id, name, price, data.discont);
          $good.render($('#goods'));
        }
      }
    });
  });
    
  $('#moreGoods').on('click', function (ev) {
    ev.preventDefault();
    let $goodsCount = $('.good').length;
    let data = {
      "count": $goodsCount,
    };
    $.ajax({
      url: '/goods/getMoreGoods',
      type: 'POST',
      dataType: 'json',
      data,
      context: this,
      error: function (err) {
        console.log('Ошибка', err);
      },
      success: function (data) {
        if (data.result) {
          let $goods = $('#goods');
          data.goods.forEach(function (item) {
              if (item.discont) {
                  if (checkDateEntrance(new Date(), item.discont_start + ',' + item.discont_end)) {
                      let good = new Good(item.id, item.name, item.price, item.discont / 100);
                      good.render($goods);
                  } else {
                      let good = new Good(item.id, item.name, item.price);
                      good.render($goods);
                  }
              } else {
                  let good = new Good(item.id, item.name, item.price);
                  good.render($goods);
              }
          });
          $goods.on('click', '.buygood', function () {
              let idProduct = parseInt($(this).attr('data-id'));
              basket.addProductAjax(idProduct);
          });
          $goods.on('click', '.delgood', function () {
              let idProduct = parseInt($(this).attr('data-id'));
              basket.dellProductAjax(idProduct);
          });
        }
      }
    });
  })
});