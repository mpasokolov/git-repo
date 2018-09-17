<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.09.2018
 * Time: 13:42
 */

namespace app\common\models;

use system\components\Model;

class Good extends Model
{
  function saveFile($id) {

    if ($_FILES['Catalog']['type']['image'] !== 'image/jpeg') {
      return false;
    }

    $uploadDir = ROOT . "/public/assets/img/";
    $uploadFile = $uploadDir . $id . '.jpg';

    return move_uploaded_file($_FILES['Catalog']['tmp_name']['image'], $uploadFile) ? true : false;
  }
}