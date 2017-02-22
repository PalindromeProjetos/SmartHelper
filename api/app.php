<?php

require '../vendor/autoload.php';

//print_r($HTTP_SERVER_VARS);

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'apiControllers'=>'Api/Controllers/'
    ]
];

$app = new \SmartFull\App($config);

$app->run();
