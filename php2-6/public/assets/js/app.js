/*
 * Created by Artyom Manchenkov
 * Copyright © 2015-2018 [DeepSide Interactive]
 */

console.log("GeekShop MVC Loaded...");

$(document).ready(function() {
    $('.catalog').on('click', '.buyGood', function (ev) {
       ev.preventDefault();
       let id_good = $(this).attr("data-id");

       $.ajax({
           url: "/cart/buy",
           type: "POST",
           dataType: "json",
           data: {
               "id_good": id_good,
               "quantity": 1
           },
           error: function(error) {
               alert("Что-то пошло не так...");
               console.log(error);
           },
           success: function(answer){
               if (answer['result'] === 1) {
                   alert("Товар успешно добавлен в корзину");
               } else if (answer['result'] === 2){
                   $(location).attr('href', 'lk/login')
               }
           },
       })
    });
    $('.cart').on('click', '.deleteGood', function (ev) {
        ev.preventDefault();
        let id_good = $(this).attr("data-id");

        $.ajax({
            url: "/cart/delete",
            type: "POST",
            dataType: "json",
            context: this,
            data: {
                "id_good": id_good,
                "quantity": 1
            },
            error: function(error) {
                alert("Что-то пошло не так...");
                console.log(error);
            },
            success: function(answer){
                let count = parseInt($(this).prev().children('.goodQuantity').text());

                if (answer['result'] === 1) {
                    $(this).prev().children('.goodQuantity').text(count - 1);
                } else if (answer['result'] === 2) {
                    $('.good').filter(function() {
                        return $(this).data("id") == id_good
                    }).remove();
                } else {
                    alert("Что-то пошло не так2...");
                }
            },
        })
    })
});

