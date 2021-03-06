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
    public function __construct(Client $client, int $throughputLimit)
    {
        $this->client = $client;
        $this->throughputLimit = $throughputLimit;
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

    /**
     * Wait for the next loop
     * @return void
     */
    public function wait(): void
    {
        usleep($this->calcWaitTime());
    }

    /**
     * return the amount of time need to wait, based on the throughputLimit
     * @return int
     */
    public function calcWaitTime(): int
    {
        return round(1000000 / $this->throughputLimit);
    }
}