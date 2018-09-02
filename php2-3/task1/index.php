<?php

require './vendor/autoload.php';
require './config/DB.php';
require './components/gallery.php';


$loader = new Twig_Loader_Filesystem('views/pages');
$twig = new Twig_Environment($loader);

$id = $_GET['id'];

$gallery = new gallery();

if (empty($id)) {
  try {
    $galleryData = $gallery->getGallery();
    $galleryData = DB::fetch_array($galleryData);
    $html = $twig->render('gallery.html', ['gallery' => $galleryData]);
    echo $html;
  } catch (Exception $e) {
    echo 'Ошибка: ', $e->getMessage(), "\n";
  }
} else {
  try {
    $gallery -> updateItemViews($id);
    $galleryData = $gallery->getItemById($id);
    $galleryData = DB::fetch_array($galleryData);
    $html = $twig->render('image.html', ['image' => $galleryData[0]]);
    echo $html;
  } catch (Exception $e) {
    echo 'Ошибка: ', $e->getMessage(), "\n";
  }
}
