<?php 

namespace App;

use MessageBird\Client;
use MessageBird\Objects\Message;

class Sender
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function sendMessage(Message $message)
    {
        return $this->client->messages->create($message);
    }
}