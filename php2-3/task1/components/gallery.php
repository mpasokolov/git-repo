<?php

class gallery {
  function  __construct() {
    DB::getInstance();
  }
  function getGallery() {
    $sql = "SELECT * FROM gallery ORDER BY views DESC";
    return DB::query($sql);
  }
  function getItemById($id) {
    $sql = "SELECT * FROM gallery WHERE id = $id";
    return DB::query($sql);
  }
  function updateItemViews($id) {
    $sql = "UPDATE gallery SET views = views + 1 WHERE id = $id";
    DB::query($sql);
  }
}