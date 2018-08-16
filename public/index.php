<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Sender;
use App\Storage;
use App\Udh;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';

function split_message($messageBody) {
    
    $splitedMessage = str_split($messageBody,153);
    $total = count($splitedMessage);
    
    foreach($splitedMessage as $key => $part)
    {
        yield [
            'udh' => (new Udh($total, ++$key))->toString(), 
            'body' => $part
        ];
    }
}

$messageText = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

$storage = new Storage();
foreach(split_message($messageText) as $messagePart) {
    $storage->add([
        'originator' => $config['originator'],
        'recipients' => [31612345678], 
        'body'       => $messagePart['body'],
        'udh'        => $messagePart['udh']
    ]);
}

$messageBirdClient = new \MessageBird\Client($config['api-key']);

$returnedData = $storage->get();
$message             = new \MessageBird\Objects\Message();
$message->originator = $returnedData['originator'];
$message->recipients = $returnedData['recipients'];
$message->setBinarySms($returnedData['udh'], $returnedData['body']);

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