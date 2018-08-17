<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Decorator\JsonDecorator;
use App\Decorator\LibSodiumDecorator;
use App\Receiver;
use App\Storage\QueueStorage;

$env = getenv('APPLICATION_ENV') ?? 'development';
$config = require __DIR__ . '/../config/' . $env . '.php';

$response = 'Welcome Message Bird!';

if ($_POST) {

    /**
     * Validate recipients as array
     */
    $recipients = is_array($_POST['recipients']) ? $_POST['recipients'] : [$_POST['recipients']];

    /**
     * Storage mode
     */
    $storage = new QueueStorage($config['queue-id']);

    /**
     * Decorator
     */
    $storage->setDecorator(new LibSodiumDecorator($config['sodium-key'], $config['sodium-nonce']));
    #$storage->setDecorator(new JsonDecorator());

    $controller = new Receiver($config, $storage);
    $response = $controller->post($recipients, $_POST['body']);

}

echo $response;