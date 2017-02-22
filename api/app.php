<?php

require '../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'apiControllers'=>'Api/Controllers/'
    ]
];

$app = new \SmartFull\App($config);

$app->run();
