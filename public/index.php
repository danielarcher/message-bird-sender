<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Sender;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';

$messageBirdClient = new \MessageBird\Client($config['api-key']); // Set your own API access key here.

$message             = new \MessageBird\Objects\Message();
$message->originator = 'MessageBird';
$message->recipients = array(31612345678);
$message->body       = 'This is a test message.';

try {
    $sender = new Sender($messageBirdClient);
    $result = $sender->sendMessage($message);

    echo "<pre>";
    var_dump($result);

} catch (\MessageBird\Exceptions\AuthenticateException $e) {
    // That means that your accessKey is unknown
    echo 'wrong login';

} catch (\MessageBird\Exceptions\BalanceException $e) {
    // That means that you are out of credits, so do something about it.
    echo 'no balance';

} catch (\Exception $e) {
    echo $e->getMessage();
}