<?php

$sql = 'SELECT 
`tableData`.`id_category`,
`tableData`.`category_name`,
`tableTree`.`parent_id`, 
`tableTree`.`nearest_parent`
FROM `categories` AS `tableData`
JOIN `category_links` AS `tableTree` 
ON `tableData`.`id_category` = `tableTree`.`child_id`
WHERE `tableTree`.`parent_id` = 1 ORDER BY `nearest_parent`';

$mysql = new mysqli('localhost', 'root', 'root','aads');

$res = $mysql->query($sql);

$categories = [];

if($res) {
    while($row = $res->fetch_assoc()) {
        $categories[] = $row;
    }
    $res->close();
}

function rebuild($categories){
    $result=[];
    foreach ($categories as $category) {
        if (!isset($result[$category['nearest_parent']])) {
            $result[$category['nearest_parent']] = [];
        }
        $result[$category['nearest_parent']][] = $category;
    }
    return $result;
}

function buildTree($categories, $cat = 0){
    $html = "<ul>";
    foreach($categories[$cat] as $category){
        $html.="<li>".$category['category_name'];

        if(isset($categories[$category['id_category']])){
            $html.=buildTree($categories,$category['id_category']);
        }
        $html.="</li>";
    }
    $html.="</ul>";
    return $html;
}

function getTree($categories){
    return buildTree(rebuild($categories));
}

echo getTree($categories);