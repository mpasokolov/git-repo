<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{SITE_TITLE}}</title>

    <link rel="stylesheet" href="/css/my.css">

    <script src="/js/jquery-3.3.1.js"></script>
    <script src="/js/cartGood.js"></script>
    <script src="/js/Good.js"></script>
    <script src="/js/Basket.js"></script>
    <script src="/js/main.js"></script>
</head>
<body>
<div id="basket_wrapper">
    <h2>Корзина:</h2>
</div>
<hr>
<div id="goods">
    <h2>Каталог товаров:</h2>
</div>
<button id="moreGoods">Показать больше товаров.</button>
<hr>
<div id="addGoods">
    <h2>Добавить новый товар:</h2>
    <form method="post" name="addGoods">
        <label>Имя товара:
            <input type="text" name="name" id="newGoodName" required>
        </label><br>
        <label>Стоимость:
            <input type="text" name="price" id="newGoodPrice" required>
        </label><br>
        <label>Категория:
            <select name="category" form="addGoods" id="newGoodCategory">
            {{CATEGORIES}}
            </select><br><br>
        </label>
        <input type="submit" value="Добавить" id="addGood">
    </form>
</div>
</body>
</html>