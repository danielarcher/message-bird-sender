<?php 

namespace App;

use App\Decorator\DecoratorInterface;
use App\Decorator\JsonDecorator;
use App\Decorator\LibSodiumDecorator;
use App\Storage\QueueStorage;
use App\Storage\StorageInterface;
use App\Udh;

class Receiver
{
    /**
     * create a new controller class, to receive post messages
     * 
     * @param array $config [description]
     */
    public function __construct(array $config, StorageInterface $storage)
    {
        $this->config = $config;
        $this->storage = $storage;
    }

    /**
     * receive recipients and a message string to send to the storage
     * @param  array  $recipients  
     * @param  string $messageText 
     * @return void
     */
    public function post(array $recipients, string $messageText): string
    {
        try {

            /**
             * Split the message and add to storage
             */
            foreach($this->split_message($messageText, $this->config['message-max-size']) as $messagePart) {
                $this->storage->add([
                    'originator'  => $this->config['originator'],
                    'recipients'  => $recipients, 
                    'body'        => $messagePart['body'],
                    'type'        => $messagePart['type'],
                    'typeDetails' => $messagePart['typeDetails'],
                ]);
            }

            http_response_code(202);

            return 'Message queued, thanks!';
        } catch (\Exception $e) {

            http_response_code(500);
            return 'Error: '.$e->getMessage();
        }
    }

    /**
     * Split the message in parts of configurated size
     * 
     * @param  string $messageBody 
     * @param  int    $size        
     * @return array
     */
    public function split_message(string $messageBody, int $size) {
        
        $splitedMessage = str_split($messageBody, $size);
        $total = count($splitedMessage);
        $ref = mt_rand(1, 255);
        foreach($splitedMessage as $key => $part)
        {
            yield [
                'type' => 'binary',
                'typeDetails' => [
                    'udh' => (new Udh($total, ++$key, $ref))->toString(), 
                ],
                'body' => $part
            ];
        }
    }
}