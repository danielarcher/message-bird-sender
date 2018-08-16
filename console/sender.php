<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Storage\FileStorage;
use App\Decorator\JsonDecorator;
use App\Sender;
use MessageBird\Client;
use MessageBird\Objects\Message;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';
$messageBirdClient = new Client($config['api-key']);

$storage = new FileStorage($config['data-file'], new JsonDecorator());
$sender = new Sender($messageBirdClient, $storage);

while (true) {
    $nextMessage = $storage->get();
    if (empty($nextMessage)) {
        sleep($config['sleep-sec']);
        continue;
    }

    $message = (new Message())->loadFromArray($nextMessage);

    #$message = new Message();
    #$message->originator = $nextMessage['originator'];
    #$message->recipients = $nextMessage['recipients'];
    #$message->setBinarySms($nextMessage)
    

    $result = $sender->sendMessage($message);
    sleep($config['sleep-sec']);
}