<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\ReceiverController;

$env = getenv('APPLICATION_ENV') ?? 'development';
$config = require __DIR__ . '/../config/' . $env . '.php';

if ($_POST) {

    $controller = new ReceiverController($config);
    $controller->post($_POST['recipients'], $_POST['body']);
    
}
