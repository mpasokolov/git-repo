<?php


//Получение добавленных товаров
function getBasket()
{
    $sql = "SELECT b.*, g.name FROM basket b INNER JOIN goods g ON g.id = b.id_product";
    $basket = getAssocResult($sql);
    return $basket;
}

function getBasketAmount($basket)
{
    $amount = 0;
    foreach ($basket as $item)
    {
        $amount += $item['price'];
    }
    return $amount;
}

function checkDiscont($discont_start, $discont_end) {
  $date = new DateTime();
  $now = $date->getTimestamp();
  $start = strtotime($discont_start);
  $end = strtotime($discont_end);
  return ($now >= $start && $now <= $end) ? true : false;
}

function getPrice($idProduct) {
  $sql = "SELECT g.*, d.discont, d.discont_start, d.discont_end 
          FROM goods as g 
          LEFT JOIN disconts AS d ON g.category = d.category_id 
          WHERE g.id = $idProduct";
  $row = getRowResult($sql);
  if ($row['discont']) {
    if (checkDiscont($row['discont_start'], $row['discont_end'])) {
      return round($row['price'] - $row['price'] * (10 / 100));
    } else {
      return $row['price'];
    }
  } else {
    return $row['price'];
  }
}

function getDiscont($idProduct) {
  $sql = "SELECT g.*, d.discont, d.discont_start, d.discont_end 
          FROM goods as g 
          LEFT JOIN disconts AS d ON g.category = d.category_id 
          WHERE g.id = $idProduct";
  $row = getRowResult($sql);
  if ($row['discont']) {
    if (checkDiscont($row['discont_start'], $row['discont_end'])) {
      return $row['discont'];
    } else {
      return 0;
    }
  }
}

function addGoodBasket($idProduct)
{
    $dbLink = getConnection();
    $idProduct = mysqli_real_escape_string($dbLink, htmlspecialchars(strip_tags($idProduct)));

    $price = getPrice($idProduct);

    if (!checkGoodInCart($idProduct)) {
      $sql = "UPDATE basket SET price = price + $price, count = count + 1 WHERE id_product = $idProduct";
      $res = executeQuery($sql, $dbLink);
      if(!$res){
        $response = ['result' => null];
      } else {
        $response = ['result' => 2];
      }
    } else {
      $sql2 = "INSERT INTO basket (id_product, count, price) VALUES ('$idProduct', '1', '$price')";
      $res = executeQuery($sql2, $dbLink);

      if(!$res){
        $response = ['result' => null];
      } else {
        $response = ['result' => 1];
      }
    }
    $sql = "SELECT b.*, g.name FROM basket b INNER JOIN goods g ON g.id = b.id_product WHERE id_product = $idProduct";
    $row = getRowResult($sql);
    $response['good'] = $row;
    return $response;
}

function delGoodBasket($idProduct)
{
  $dbLink = getConnection();
  $idProduct = mysqli_real_escape_string($dbLink, htmlspecialchars(strip_tags($idProduct)));

  if (getGoodCount($idProduct) > 1) {
    $sql = "UPDATE basket SET price = price - price / count, count = count - 1 WHERE id_product = $idProduct";
    $res = executeQuery($sql, $dbLink);

    if(!$res){
      $response = ['result' => false];
    } else {
      $sql = "SELECT * FROM basket WHERE id_product = $idProduct";
      $row = getRowResult($sql);
      $response = ['result' => 1, 'count' => $row['count'], 'price' => $row['price']];
    }
  } else {
    $sql = "DELETE FROM basket WHERE id_product = $idProduct";
    $res = executeQuery($sql, $dbLink);
    if(!$res){
      $response = ['result' => false];
    } else {
      $response = ['result' => 2];
    }

  }
  return $response;
}

function getGoodCount($idProduct) {
  $sql = "SELECT * FROM basket WHERE id_product = $idProduct";
  $row = getRowResult($sql);
  if ($row) {
    return $row['count'];
  } else {
    return null;
  }

}

function checkGoodInCart($id) {
  $sql = "SELECT * FROM basket WHERE id_product = $id";
  $row = getRowResult($sql);
  if (empty($row)) {
    return true;
  } else {
    return false;
  }
}

function getGoodInCart($id) {
  $sql = "SELECT * FROM basket WHERE id_product = $id";
  $result = getRowResult($sql);
  if ($result) {
    $response = ['result' => true, 'good' => $result];
  } else {
    $response = ['result' => null];
  }
  return $response;
}

function changeCartGoodCount($id, $count) {
  $sql = "UPDATE basket b INNER JOIN goods g ON b.id_product = g.id SET b.count = $count, b.price = g.price * $count WHERE b.id_product = $id";
  $res = executeQuery($sql);
  if ($res) {
    $sql = "SELECT * FROM basket WHERE id_product = $id";
    $result = getRowResult($sql);
    $response = ['result' => true, 'price' => $result['price']];
  } else {
    $response = ['result' => false];
  }
  return $response;
}

