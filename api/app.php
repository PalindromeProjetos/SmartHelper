<?php

require '../vendor/autoload.php';

print_r($_SERVER);

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'apiControllers'=>'Api/Controllers/'
    ]
];

$app = new \SmartFull\App($config);

$app->run();
