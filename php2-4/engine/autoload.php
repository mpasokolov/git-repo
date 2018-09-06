<?php

$filesLibs = scandir(LIB_DIR);

foreach ($filesLibs as $file)
{
    if($file != '.' && $file != '..'){
        if(substr($file, -8) == '.lib.php'){
            include_once (LIB_DIR . '/' . $file);
        }
    }
}