<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Receiver;

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
    $storage = new QueueStorage($this->config['queue-id']);

    /**
     * Decorator
     */
    $storate->setDecorator(new LibSodiumDecorator($this->config['sodium-key'], $this->config['sodium-nonce']));

    $controller = new Receiver($config, $storage, $decorator);
    $response = $controller->post($recipients, $_POST['body']);

}


return $response;