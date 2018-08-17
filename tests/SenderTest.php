<?php 

use App\Sender;
use MessageBird\Client;
use MessageBird\Objects\Message;
use MessageBird\Resources\Messages;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSendMessage()
    {
        $mockClient = $this->getMockBuilder(Client::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $mockClient->messages = $this->getMockBuilder(Messages::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        
        $mockMessage = $this->getMockBuilder(Message::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        
        $mockClient->messages->method('create')
                            ->willReturn(true);


        $sender = new Sender($mockClient);
        $this->assertTrue($sender->sendMessage($mockMessage));
    }
}