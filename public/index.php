<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\ReceiverController;

$env = getenv('APPLICATION_ENV') ?? 'development';
$config = require __DIR__ . '/../config/' . $env . '.php';

if ($_POST) {
    $recipients = is_array($_POST['recipients']) ? $_POST['recipients'] : [$_POST['recipients']];

    $controller = new ReceiverController($config);

    echo $controller->post($recipients, $_POST['body']);
}
