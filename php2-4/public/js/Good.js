class Good
{
    constructor(id, title, price, koef)
    {
        this.id = id;
        this.title = title;
        this.price = price;
        this.oldName = '';
        this.oldPrice = null;
        this.koef = koef ? koef : 0;
    }

    render($jqueryElement)
    {
        let $goodContainer = $('<div/>', {
            class: 'good'
        });

        let $goodTitle = $('<span/>', {
            text: this.title,
            class: 'product-title',
        });

        let $goodTitleChange = $('<span class="product-name-modify">Изменить</span>');

        let self = this;
        $goodContainer.on('click', '.product-name-modify', function () {
          self.modifyGoodName($goodTitleChange);
        });

        let $goodPrice;

        if ( this.koef !== 0 ) {
            $goodPrice = $(`<p>Цена: <span class="product-price product-old-price" data-id="${this.id}">${this.price}</span>
            <span class="product-new-price" data-id="${this.id}">${Math.round(this.price - this.price * this.koef)}</span> руб.</p>`);
        } else {
            $goodPrice = $(`<p>Цена: <span class="product-price" data-id="${this.id}">${this.price}</span> руб.</p>`);
        }

        let $goodPriceChange = $('<span class="product-price-modify">Изменить</span>');
        $goodPriceChange.appendTo($goodPrice);

        if ( this.koef !== 0 ) {
            let $goodDiscont = $(`<span class="product-discont">Скидка: ${this.koef * 100}%</span>`);
            $goodDiscont.appendTo($goodPrice);
        }

        $goodContainer.on('click', '.product-price-modify', function () {
          self.modifyGoodPrice($goodPriceChange, self.id);
        });

        let $goodBtn = $('<button/>', {
              class: 'buygood',
              text: 'Купить',
              'data-id': this.id
        });

        let $goodDelBtn = $('<button/>', {
          class: 'delgood',
          text: 'Удалить',
          'data-id': this.id
        });

        //Создаем структуру товара
        $goodTitle.appendTo($goodContainer);
        $goodTitleChange.appendTo($goodContainer);
        $goodPrice.appendTo($goodContainer);
        $goodBtn.appendTo($goodContainer);
        $goodDelBtn.appendTo($goodContainer);
        $jqueryElement.append($goodContainer);
    }

    modifyGoodName($jqueryElement) {
      let nameEl = $jqueryElement.prev()[0];
      if ($jqueryElement.prev()[0].nodeName === 'SPAN') {
        this.oldName = $jqueryElement.prev().text();
        let el = $('<textarea>', {class: 'product-title', 'data-id': this.id});
        $jqueryElement.prev().replaceWith(el);
        $jqueryElement.text('Сохранить');
      } else {
        let name = nameEl.value;
        if (name) {
          this.changeGoodName(this.id, name, $jqueryElement);
        } else {
          let el = $('<span>', {class: 'product-title', 'data-id': this.id, text: this.oldName});
          $jqueryElement.prev().replaceWith(el);
          $jqueryElement.text('Изменить');
        }
      }
    }

    modifyGoodPrice($jqueryElement, id) {
      let $priceEl = $('.product-price').filter(function(){
          return $(this).data("id") === +id});
      if ($priceEl[0].nodeName === 'SPAN') {
        this.oldPrice = $priceEl.text();
        let $el = $('<textarea>', {class: 'product-price', 'data-id': this.id});
        $priceEl.replaceWith($el);
        $jqueryElement.text('Сохранить');
      } else {
        let price = parseInt($priceEl.val());
        if (price) {
          this.changeGoodPrice(this.id, price, $jqueryElement);
        } else {
            let class_str = this.koef > 0 ? 'product-price product-old-price' : 'product-price';
            let el = $('<span>', {class: class_str, 'data-id': this.id, text: this.oldPrice});
            $priceEl.replaceWith(el);
            $jqueryElement.text('Изменить');
        }
      }
    }

    changeGoodName(id, name, $jqueryElement) {
      $.ajax({
        url: '/basket/changeGoodName',
        type: 'POST',
        dataType: 'json',
        data: {'id_product': id, 'name': name},
        context: this,
        error: function (err) {
          console.log('Ошибка', err);
        },
        success: function (data) {
          if (data.result) {
            let el = $('<span>', {class: 'product-title', 'data-id': this.id, text: name});
            $jqueryElement.prev().replaceWith(el);
            $jqueryElement.text('Изменить');
            $('.product-title').filter(function(){
              return $(this).data("id") === +id})[0].textContent = name;
            $('.product-cart-name').filter(function(){
              return $(this).data("id") === +id}).text(name);
          }
        }
      });
    }

    changeGoodPrice(id, price, $jqueryElement) {
      let data = {
        "id_product": id,
        "price": price
      };
      $.ajax({
        url: '/basket/changeGoodPrice',
        type: 'POST',
        dataType: 'json',
        data,
        context: this,
        error: function (err) {
          console.log('Ошибка', err);
        },
        success: function (data) {
        let $priceEl = $('.product-price').filter(function(){
            return $(this).data("id") === +id});
        if (data.result) {
            let class_str = this.koef > 0 ? 'product-price product-old-price' : 'product-price';
            let el = $('<span>', {class: class_str, 'data-id': this.id, text: price});
            $priceEl.replaceWith(el);
            $jqueryElement.text('Изменить');
            $priceEl.val(price);
            if (data.discont) {
                $('.product-new-price').filter(function () {
                    return $(this).data("id") === +id
                }).text(price - price * (data.discont / 100));
            }
            if (data.price) {
                if (data.discont) {
                    $('.product-cart-price').filter(function () {
                        return $(this).data("id") === +id
                    }).text(data.price - data.price * (data.discont / 100));

                } else {
                    $('.product-cart-price').filter(function () {
                        return $(this).data("id") === +id
                    }).text(data.price);
                }
            }
        }
        }
      });
    }
}