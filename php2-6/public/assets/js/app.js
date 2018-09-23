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
    });
    $('#doTests').on('click', function (ev) {
        ev.preventDefault();

        $.ajax({
            url: "/admin/tests/do",
            type: "POST",
            data: {},
            dataType: "json",
            error: function(error) {
                alert("Что-то пошло не так...");
                console.log(error);
            },
            success: function(answer){
                $('.tests').empty();
                if (answer['result'] === 1) {
                    renderTestsResult(answer['data']);
                }
            },
        })
    });

    function renderTestsResult(data) {
        data.forEach(function (el) {
            let testBlock = $('<p/>', {
                class: 'testBlock',
            });
            let testName = $('<span/>', {
                text: 'Name: ' + el['name'],
                class: 'testName testFiled',
            });
            testName.appendTo(testBlock);
            let testAssertions = $('<span/>', {
                text: 'Assertions: ' + el['assertions'],
                class: 'testAssertions testFiled',
            });
            testAssertions.appendTo(testBlock);
            let testErrors = $('<span/>', {
                text: 'Errors: ' + el['errors'],
                class: 'testErrors testFiled',
            });
            testErrors.appendTo(testBlock);
            let testFailures = $('<span/>', {
                text: 'Failures: ' + el['failures'],
                class: 'testFailures testFiled',
            });
            testFailures.appendTo(testBlock);
            let testSkipped = $('<span/>', {
                text: 'Skipped: ' + el['skipped'],
                class: 'testSkipped testFiled',
            });
            testSkipped.appendTo(testBlock);
            let testTime = $('<span/>', {
                text: 'Time: ' + el['time'],
                class: 'testTime testFiled',
            });
            testTime.appendTo(testBlock);
            testBlock.appendTo($('.tests'));
        })
    }
});

