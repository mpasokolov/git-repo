<?php

function getData($lvl) {
    $sql = "SELECT * FROM `nested` WHERE depth >= $lvl ORDER BY l ASC";

    $mysql = new mysqli('localhost', 'root', 'root', 'aads');
    $res = $mysql->query($sql);

    $categories = [];

    if ($res) {
        while ($row = $res->fetch_object()) {
            $categories[] = $row;
        }
        $res->close();

    }
    
    return $categories;
}

function getTree($level_start = 0) {
    $categories = getData($level_start);
    $level = $level_start;
    $menu = null;
    foreach ($categories as $key => $category) {
        switch ($category->depth) {
            case ($category->depth == $level):
                $menu .= "</li>";
                break;
            case ($category->depth > $level):
                $menu .= "<ul>";
                break;
            case ($category->depth < $level):
                $menu .= "</li>";
                for ($i = $level - $category->depth; $i; $i--) {
                    $menu .= "</ul>";
                    $menu .= "</li>";
                }
                break;
        };
        $menu .= "<li>";
        $menu .= "<a>" . $category->value . "</a>";
        
        $level = $category->depth;
    }
    for ($i = $level; $i > $level_start; $i--) {
        $menu .= "</li>";
        $menu .= "</ul>";
    }
    
    return $menu;
}

echo getTree();