<?php
$array = [ 1, 5, 3, 8, 2, 2, 0, 8, -4, 9, 12, 2, 6, 10000];

function mergeSort($array) {
    $middle = ceil(count($array) / 2);
    $arr1 = array_slice($array, 0, $middle);
    $arr2 = array_slice($array, $middle);

    if (count($arr1) > 2) $arr1 = mergeSort($arr1);
    if (count($arr2) > 2) $arr2 = mergeSort($arr2);
    
    if (count($arr1) === 2) {
        if ($arr1[0] > $arr1[1]) {
            list($arr1[0], $arr1[1]) = array($arr1[1], $arr1[0]);
        }
    }
    
    if (count($arr2) === 2) {
        if ($arr2[0] > $arr2[1]) {
            list($arr2[0], $arr2[1]) = array($arr2[1], $arr2[0]);
        }
    }
    
    $i = 0; 
    $j = 0;
    $arr2[] = INF;
    $result = [];

    while ($i < count($arr1)) {
        if ($arr1[$i] > $arr2[$j]) {
            $result[] = $arr2[$j];
            $j++;
            continue;
        } else {
            $result[] = $arr1[$i];
            $i++;
            continue;
        }
    }

    while ($j < count($arr2) - 1) {
        $result[] = $arr2[$j];
        $j++;
    }

    return $result;
}

$array = mergeSort($array);
var_dump($array);


