<?php

$str = 'ada';

function palindromeCheck($str, $start, $end) {
    $i = $start;
    $j = $end;
    while ($i < $j) {
        if ($str[$i] !== $str[$j]) {
            return false;
        }
        $i++; $j--;
        palindromeCheck($str, $i, $j);
    }
    return true;
}

var_dump(palindromeCheck($str, 0, strlen($str) - 1));
