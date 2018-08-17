<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Decorator\JsonDecorator;
use App\Logger;
use App\Sender;
use App\Storage\QueueStorage;
use MessageBird\Client;
use MessageBird\Objects\Message;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';

$messageBirdClient = new Client($config['api-key']);

$logger = new Logger($config['log-file']);

$storage = new QueueStorage($config['queue-id']);
$storage->setDecorator(new JsonDecorator());

$sender = new Sender($messageBirdClient);

while (true) {
    $nextMessage = $storage->get();
    if (empty($nextMessage)) {
        
        /**
         * Any messages to proccess, show bar and wait
         */
        echo progress_bar($storage->getTotal(), 100);
        sleep($config['sleep-sec']);
        continue;
    }

    try {
        $message = new Message();
        $message->originator = $nextMessage['originator'];
        $message->recipients = $nextMessage['recipients'];
        $message->setBinarySms($nextMessage['typeDetails']['udh'], $nextMessage['body']);

        $result = $sender->sendMessage($message);
        $logger->info('Message sent! id: '. $result->getId());
    } catch (\Exception $e) {
        $logger->error($e->getMessage());
        echo "Error: ".$e->getMessage()."\n";
    }

        /**
         * Show progressbar and wait
         */
        echo progress_bar($storage->getTotal(), 100);
        sleep($config['sleep-sec']);
        
}
/**
 * Generate a cli progressBar with very low complexity
 * 
 * @param  int     $done
 * @param  int     $total
 * @param  string  $info
 * @param  integer $width
 * @return string
 */
function progress_bar(int $done, int $total, string $info="", $width=50): string {
    $perc = round(($done * 100) / $total);
    $bar = round(($width * $perc) / 100);
    return sprintf(" -> Queue total: %s [%s %s]%s\r", $perc, str_repeat("#", $bar), str_repeat(" ", $width-$bar), $info);
}