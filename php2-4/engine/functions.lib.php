<?php
function prepareVariables($pageName, $action)
{
    $vars = []; //Для передачи в шаблонизатор

    $vars['site_title'] = SITE_TITLE;

    switch ($pageName) {
        case 'basket':
            if($action === 'get'){
                $basketArray = ["result" => 1, ];
                $basketArray['basket'] = getBasket();
                $vars['response_json'] = $basketArray;
            }

            if ($action === 'add' && isset($_POST['id_product'])){
                $idProduct = htmlspecialchars(strip_tags($_POST['id_product']));
                $vars['response_json'] = addGoodBasket($idProduct);
            }

            if ($action === 'del' && isset($_POST['id_product'])) {
              $idProduct = htmlspecialchars(strip_tags($_POST['id_product']));
              $vars['response_json'] = delGoodBasket($idProduct);
            }

            if ($action === 'delGood' && isset($_POST['id_product'])) {
              $idProduct = htmlspecialchars(strip_tags($_POST['id_product']));
              $vars['response_json'] = delGood($idProduct);
            }

            if ($action === 'changeCartGoodCount' && isset($_POST['id_product']) && isset($_POST['count'])) {
              $idProduct = htmlspecialchars(strip_tags($_POST['id_product']));
              $count = htmlspecialchars(strip_tags($_POST['count']));
              $vars['response_json'] = changeCartGoodCount($idProduct, $count);
            }
            if ($action === 'changeGoodName' && isset($_POST['id_product']) && isset($_POST['name'])) {
              $idProduct = htmlspecialchars(strip_tags($_POST['id_product']));
              $name = htmlspecialchars(strip_tags($_POST['name']));
              $vars['response_json'] = changeGoodName($idProduct, $name);
            }
            if ($action === 'changeGoodPrice' && isset($_POST['id_product']) && isset($_POST['price'])) {
              $idProduct = htmlspecialchars(strip_tags($_POST['id_product']));
              $price = htmlspecialchars(strip_tags($_POST['price']));
              $vars['response_json'] = changeGoodPrice($idProduct, $price);
            }
            break;
        case 'goods':
            $vars['categories'] = getCategories();
            renderPage($pageName, $vars);
            if($action === 'addNewGood' && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['category'])){
              $name = htmlspecialchars(strip_tags($_POST['name']));
              $price = (int)htmlspecialchars(strip_tags($_POST['price']));
              $category = (int)htmlspecialchars(strip_tags($_POST['category']));
              $vars['response_json'] = addNewGood($name, $price, $category);
            }
            if($action === 'getGoods'){
                $goodsArray = ["result" => 1, ];
                $goodsArray['goods'] = getGoods();
                $vars['response_json'] = $goodsArray;
            }
            if($action === 'getCartGoods'){
                $cartGoodsArray = ["result" => 1, ];
                $cartGoodsArray['cartGoods'] = getBasket();
                $vars['response_json'] = $cartGoodsArray;
            }
            if ($action === 'getMoreGoods' && isset($_POST['count'])) {
              $goods = ['result' => 1];
              $goods['goods'] = getGoods($_POST['count']);
              $vars['response_json'] = $goods;
            }
            break;
    }
    return $vars;
}