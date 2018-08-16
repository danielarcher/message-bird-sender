<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Sender;
use App\Decorator\JsonDecorator;
use App\Storage\QueueStorage;
use MessageBird\Client;
use MessageBird\Objects\Message;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';

$messageBirdClient = new Client($config['api-key']);


$storage = new QueueStorage($config['queue-id']);
#$storage->setDecorator(new JsonDecorator());
$sender = new Sender($messageBirdClient, $storage);

while (true) {
    $nextMessage = $storage->get();
    if (empty($nextMessage)) {
        echo progress_bar($storage->getTotal(), 100);
        sleep($config['sleep-sec']);
        continue;
    }

    $message = new Message();
    $message->originator = $nextMessage['originator'];
    $message->recipients = $nextMessage['recipients'];
    $message->setBinarySms($nextMessage['typeDetails']['udh'], $nextMessage['body']);

    $result = $sender->sendMessage($message);

    echo progress_bar($storage->getTotal(), 100);

    sleep($config['sleep-sec']);
}

function progress_bar($done, $total, $info="", $width=50) {
    $perc = round(($done * 100) / $total);
    $bar = round(($width * $perc) / 100);
    return sprintf(" -> Queue total: %s [%s %s]%s\r", $perc, str_repeat("#", $bar), str_repeat(" ", $width-$bar), $info);
}