class cartGood
{
  constructor(id, count, price, name)
  {
    this.id = id;
    this.count = count;
    this.price = price;
    this.name = name;
    this.oldCount = null;
  }

  render($jqueryElement)
  {
    let $cartGoodContainer = $('<div/>', {
      class: 'cartGood',
      'data-id': this.id
    });

    let $goodName = $(`<p>Имя товара: <span class="product-cart-name" data-id=${this.id}>${this.name}</span></p>`);

    let $goodCount = $(`<p>Количество: <span class="product-cart-count" data-id=${this.id}>${this.count}</span></p>`);

    let $goodCountModify = $('<span class="product-cart-count-modify">Изменить</span>');

    let self = this;
    $cartGoodContainer.on('click', '.product-cart-count-modify', function () {
      self.modifyCartGood($goodCountModify);
    });

    $goodCountModify.appendTo($goodCount);

    let $goodPrice = $(`<p>Стоимость: <span class="product-cart-price" data-id=${this.id}>${this.price}</span> руб.</p>`);

    let $removeBtn = $('<button/>', {
      class: 'removeCartGood',
      text: 'Удалить',
      'data-id': this.id,
    });

    //Создаем структуру товара
    $goodName.appendTo($cartGoodContainer);
    $goodCount.appendTo($cartGoodContainer);
    $goodPrice.appendTo($cartGoodContainer);
    $removeBtn.appendTo($cartGoodContainer);
    $jqueryElement.append($cartGoodContainer);
  }

  modifyCartGood($jqueryElement) {
    let countEl = $jqueryElement.prev()[0];
    if ($jqueryElement.prev()[0].nodeName === 'SPAN') {
      this.oldCount= $jqueryElement.prev().text();
      let el = $('<textarea>', {class: 'product-cart-count', 'data-id': this.id});
      $jqueryElement.prev().replaceWith(el);
      $jqueryElement.text('Сохранить');
    } else {
      let count = parseInt(countEl.value);
      if (count) {
        this.changeCartGoodCount(this.id, count, $jqueryElement);
      } else {
        let el = $('<span>', {class: 'product-cart-count', 'data-id': this.id, text: this.oldCount});
        $jqueryElement.prev().replaceWith(el);
        $jqueryElement.text('Изменить');
      }
    }
  }

  changeCartGoodCount(id, count, $jqueryElement) {
    let data = {
      "id_product": id,
      "count": count
    };
    $.ajax({
      url: '/basket/changeCartGoodCount',
      type: 'POST',
      dataType: 'json',
      data,
      context: this,
      error: function (err) {
        console.log('Ошибка', err);
      },
      success: function (data) {
        if (data.result) {
          let el = $('<span>', {class: 'product-cart-count', 'data-id': this.id, text: count});
          $jqueryElement.prev().replaceWith(el);
          $jqueryElement.text('Изменить');
          $('.product-cart-price').filter(function(){
            return $(this).data("id") === +id})[0].textContent = data.price;
        }
      }
    });
  }
}