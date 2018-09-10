<?php

class IndexController extends Controller
{
    public $view = 'index';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= ' | Главная';
    }

    function index($data) {
      $categories = Category::getCategories(isset($data['id']) ? $data['id'] : 0);
      $goods = Good::getGoods(isset($data['id']) ? $data['id'] : 0);
      return ['subcategories' => $categories, 'goods' => $goods];
    }


}