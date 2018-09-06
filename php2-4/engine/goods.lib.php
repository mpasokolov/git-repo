<?php
function getGoods($start = 0) {
    $sql = "SELECT g.*, d.discont, d.discont_start, d.discont_end FROM goods as g LEFT JOIN disconts AS d ON g.category = d.category_id ORDER BY g.id LIMIT 3 OFFSET $start";
    $goods = getAssocResult($sql);
    return $goods;
}

function changeGoodName($id, $name) {
  $sql = "UPDATE goods SET name = '$name' WHERE id = $id";
  $res = executeQuery($sql);
  if ($res) {
    $response = ['result' => true];
  } else {
    $response = ['result' => false];
  }
  return $response;
}

function changeGoodPrice($id, $price) {
  $sql = "UPDATE goods SET price = '$price' WHERE id = $id";
  $res = executeQuery($sql);
  $discont = getDiscont($id);
  if ($res) {
    $newPrice = updateCartGoodPrice($id, $price);
    $response = ['result' => true, 'price' => $newPrice, 'discont' => $discont];
  } else {
    $response = ['result' => false, 'discont' => $discont];
  }
  return $response;
}

function updateCartGoodPrice($id, $price) {
  $sql = "SELECT * FROM basket WHERE id_product = $id";
  $row = getRowResult($sql);

  if (empty($row)) { return false; }

  $count = (int)$row['count'];
  $sql = "UPDATE basket SET price = $price * $count WHERE id_product = $id";
  $res = executeQuery($sql);
  return $res ? $price * $count : false;
}

function delGood($id) {
  $sql = "DELETE FROM goods WHERE id = $id";
  $res = executeQuery($sql);
  if ($res) {
    $response = ['result' => true];
  } else {
    $response = ['result' => false];
  }
  return $response;
}

function addNewGood($name, $price, $category) {
  $dbLink = getConnection();
  $sql = "INSERT INTO goods (name, price, category) VALUES ('$name', $price, $category)";
  $res = mysqli_query($dbLink, $sql);
  $id = mysqli_insert_id($dbLink);

  $discont = getDiscont($id) / 100;
  if ($res) {
    $response = ['result' => true, 'id' => $id, 'discont' => $discont];
  } else {
    $response = ['result' => false];
  }
  return $response;
}

function getCategories() {
  $sql = "SELECT * FROM category";
  $categories = getAssocResult($sql);
  return $categories;
}
