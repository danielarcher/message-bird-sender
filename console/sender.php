<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Decorator\JsonDecorator;
use App\Decorator\LibSodiumDecorator;
use App\Logger;
use App\Sender;
use App\Storage\QueueStorage;
use MessageBird\Client;
use MessageBird\Objects\Message;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';


$messageBirdClient = new Client($config['api-key']);

/**
 * Set Logger
 */
$logger = new Logger($config['log-file']);

/**
 * Storage Mode
 */
$storage = new QueueStorage($config['queue-id']);

/**
 * Set Decorator
 */
$storage->setDecorator(new LibSodiumDecorator($config['sodium-key'], $config['sodium-nonce']));
#$storage->setDecorator(new JsonDecorator());

$sender = new Sender($messageBirdClient, $config['limit-messages-per-second']);

while (true) {

    $nextMessage = $storage->get();

    /**
     * Verify if have a next message
     */
    if (empty($nextMessage)) {
        /**
         * Any messages to proccess, wait
         */
        $sender->wait();
        continue;
    }

    try {

        /**
         * Create the message entity
         */
        $message = new Message();
        $message->originator = $nextMessage['originator'];
        $message->recipients = $nextMessage['recipients'];
        $message->setBinarySms($nextMessage['typeDetails']['udh'], $nextMessage['body']);

        /**
         * Send the mesage to the api-client
         */
        $result = $sender->sendMessage($message);

        http_response_code(201);
        $logger->info('Message sent! id: '. $result->getId());
    } catch (\MessageBird\Exceptions\AuthenticateException $e) {
        
        http_response_code(401);
        $logger->error($e->getMessage(), 401);
    } catch (\MessageBird\Exceptions\BalanceException $e) {
        
        http_response_code(402);
        $logger->error($e->getMessage(), 402);
    } catch (\Exception $e) {

        http_response_code(500);
        $logger->error($e->getMessage(), 500);
    }

    $sender->wait();
}