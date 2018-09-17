<?php

return [
    'defaultRoute' => 'site/index',

    'controllerNamespace' => 'app\\'.ENV.'\controllers',

    'app' => [
        'name' => 'GeekShop',
    ],

    'components' => [
        'twig' => [
            'templates' => ROOT . '/app/'. ENV .'/views',
            //'cache' => ROOT.'/system/cache',
            'cache' => false,
        ]
    ]
];