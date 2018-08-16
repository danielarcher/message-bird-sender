<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Storage\FileStorage;
use App\Decorator\LibSodiumDecorator;
use App\Sender;
use MessageBird\Client;
use MessageBird\Objects\Message;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';

if (empty(file_get_contents($config['private-key-file']))) {
    $privateKey = sodium_crypto_box_keypair();
    file_put_contents($config['private-key-file'], $privateKey);
    file_put_contents($config['public-key-file'], sodium_crypto_box_publickey($privateKey));
}

$config['private-key'] = file_get_contents($config['private-key-file']);
$config['public-key'] = file_get_contents($config['public-key-file']);

$messageBirdClient = new Client($config['api-key']);

$storage = new FileStorage($config['data-file'], new LibSodiumDecorator($config['public-key'], $config['private-key']));
$sender = new Sender($messageBirdClient, $storage);

echo "<pre>";

while (true) {
    $nextMessage = $storage->get();
    if (empty($nextMessage)) {
        echo "nenhum mensagem encontrada... aguardando \n";
        sleep($config['sleep-sec']);
        continue;
    }

    print_r($nextMessage);
    #$message = (new Message())->loadFromArray($nextMessage);

    $message = new Message();
    $message->originator = $nextMessage['originator'];
    $message->recipients = $nextMessage['recipients'];
    $message->setBinarySms($nextMessage['typeDetails']['udh'], $nextMessage['body']);

    $result = $sender->sendMessage($message);

    print_r($result);
    sleep($config['sleep-sec']);
}