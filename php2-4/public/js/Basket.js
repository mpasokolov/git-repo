class Basket
{
    constructor(idBasket)
    {
        this.id = idBasket;
        this.amount = 0; //Общая стоимость товаров
    }

    render($jQueryElement)
    {
        let $basketDiv = $('<div/>', {
           id: this.id,
        });

        let $basketItemsDiv = $('<div/>', {
            id: this.id + '_items'
        });

        $basketItemsDiv.appendTo($basketDiv);
        $basketDiv.appendTo($jQueryElement);
    }

    ajaxGetCartItems()
    {
      //Корзина
      $.ajax({
        url: '/goods/getCartGoods',
        type: 'GET',
        dataType: 'json',
        context: this,
        error: function (err) {
          console.log('Ошибка', err);
        },
        success: function (data) {
          if (data.result) {
            //Корзина
            let self = this;
            data.cartGoods.forEach(function (item) {
              let good = new cartGood(item.id_product, item.count, item.price, item.name);
              good.render($('#basket_items'));
            });

            //Удаление товара из корзины
            $('#basket_items').on('click', 'button.removeCartGood' ,function (ev) {
              ev.preventDefault();
              let idProduct = parseInt($(this).attr('data-id'));
              self.delProductAjax(idProduct);
            });
          } else {
            console.log('Не удалось получить товары. Повторите попытку позже.');
          }
        }
      });
    }
    dellProductAjax(idProduct) {
      let data = {
        "id_product": idProduct,
      };
      $.ajax({
        url: '/basket/delGood',
        type: 'POST',
        dataType: 'json',
        data,
        context: this,
        error: function (err) {
          console.log('Ошибка', err);
        },
        success: function (data) {
          if (data.result) {
            $('.delgood').filter(function(){
              return $(this).data("id") === +idProduct}).parent().remove();
          } else {
            console.log('Не удалось удалить товар!');
          }
        }
      });
    }

    addProductAjax(idProduct) {
      let data = {
        "id_product": idProduct,
      };
        $.ajax({
            url: '/basket/add',
            type: 'POST',
            dataType: 'json',
            data,
            context: this,
            error: function (err) {
              console.log('Ошибка', err);
            },
            success: function (data) {
              let good = data.good;
              if (data.result === 1) {
                let newGood = new cartGood(good.id_product, good.count, good.price, good.name);
                newGood.render($('#basket_items'));
              } else if (data.result === 2) {
                this.changeCountInCart(good.id_product, good.count);
                this.changePriceInCart(good.id_product, good.price);
              }
            }
        });
    }

    delProductAjax(idProduct)
    {
      let data = {
        "id_product": idProduct,
      };

      $.ajax({
          url: '/basket/del',
          type: 'POST',
          dataType: 'json',
          context: this,
          data,
          error: function (err) {
            console.log('Ошибка', err);
          },
          success: function (data) {
            if(data.result === 1){
              this.changeCountInCart(idProduct, data.count);
              this.changePriceInCart(idProduct, data.price);
            } else if (data.result === 2) {
              this.deleteCartInCount(idProduct);
            }
          }
        });
    }

    deleteCartInCount($id) {
      $('.cartGood').filter(function(){
        return $(this).data("id") === +$id}).remove();
    }
    changeCountInCart($id, $count) {
      $('.product-cart-count').filter(function(){
        return $(this).data("id") === +$id}).text($count);
    }
    changePriceInCart($id, $price) {
      $('.product-cart-price').filter(function(){
        return $(this).data("id") === +$id}).text($price);
    }
}