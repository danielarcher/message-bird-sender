<?php 

namespace App\Controller;

use App\Decorator\JsonDecorator;
use App\Storage\QueueStorage;
use App\Udh;

class ReceiverController
{
    /**
     * create a new controller class, to receive post messages
     * 
     * @param array $config [description]
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * receive recipients and a message string to send to the storage
     * @param  array  $recipients  
     * @param  string $messageText 
     * @return void
     */
    public function post(array $recipients, string $messageText): void
    {
        $storage = new QueueStorage($this->config['queue-id']);
        $storage->setDecorator(new JsonDecorator());

        foreach($this->split_message($messageText, $this->config['message-max-size']) as $messagePart) {
            $storage->add([
                'originator'  => $this->config['originator'],
                'recipients'  => $recipients, 
                'body'        => $messagePart['body'],
                'type'        => $messagePart['type'],
                'typeDetails' => $messagePart['typeDetails'],
            ]);
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
        
        foreach($splitedMessage as $key => $part)
        {
            yield [
                'type' => 'binary',
                'typeDetails' => [
                    'udh' => (new Udh($total, ++$key))->toString(), 
                ],
                'body' => $part
            ];
        }
    }
}