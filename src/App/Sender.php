<?php 

namespace App;

use App\Storage\StorageInterface;
use MessageBird\Client;
use MessageBird\Objects\Message;

/**
 * The Sender is responsible for sending messages to the client sdk
 */
class Sender
{
    /**
     * create a new sender with the selected client
     * 
     * @param MessageBird\Client $client 
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the selected message to the client
     * @param  Message $message 
     * @return mixed
     */
    public function sendMessage(Message $message)
    {
        return $this->client->messages->create($message);
    }
}