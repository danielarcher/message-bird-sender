<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\ReceiverController;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';

if ($_POST) {
    $controller = new ReceiverController($config);
    $controller->post($_POST['recipients'], $_POST['body']);
}
