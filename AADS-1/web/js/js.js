$(document).ready(function() {
    let chat = new WebSocket('ws://localhost:8080');

    let response_field = $('#response');
    let chat_filed = $('#chat');
    let message_field = $('#message');
    let username_field = $('#username');

    chat.onmessage = function(e) {
        response_field.text('');

        let response = JSON.parse(e.data);
        if (response.type && response.type === 'chat') {
            chat_filed.append('<div>Пользователь с ником <b>' + response.from + '</b> добавил значение <b>' + response.message + '</b> в очередь!</div>');
            chat_filed.scrollTop = chat_filed.height;
        } else if (response.type && response.type === 'get') {
            chat_filed.append('<div>Элемент со значением <b>' + response.message + '</b> удален из очереди!</div>');
        } else if (response.type && response.type === 'empty') {
            chat_filed.append('<div>Очередь пуста, удалять нечего!</div>');
        } else if (response.message) {
            response_field.text(response.message);
        }
    };

    chat.onopen = function(e) {
        response_field.text("Соединение установлено! Пожалуйста введите свое имя!");
    };

    $('#btnSend').click(function() {
        if (message_field.val()) {
            chat.send( JSON.stringify({'action' : 'chat', 'message' : message_field.val()}) );
        } else {
            alert('Введите сообщение')
        }
    });

    $('#btnGet').click(function() {
        chat.send( JSON.stringify({'action' : 'getLast'}) );
    });

    $('#btnSetUsername').click(function() {
        if (username_field.val()) {
            chat.send( JSON.stringify({'action' : 'setName', 'name' : username_field.val()}) );
        } else {
            alert('Введите ваше имя')
        }
    })
});