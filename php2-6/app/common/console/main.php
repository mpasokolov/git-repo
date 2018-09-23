<?php

$cache = new Memcached();

$cache -> addServer('127.0.0.1', 11211);

$cache -> set('test', 100);

var_dump($cache -> get('test'));
