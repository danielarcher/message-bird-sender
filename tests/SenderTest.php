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
        
        $mockClient->messages->expects($this->exactly(1))
                             ->method('create')
                             ->willReturn(true);


        $sender = new Sender($mockClient, 0);
        $this->assertTrue($sender->sendMessage($mockMessage));
    }

    public function testThroughputLimitWait()
    {
        $mockClient = $this->getMockBuilder(Client::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $limit = 1;
        $sender = new Sender($mockClient, $limit);
        $this->assertEquals(1000000, $sender->calcWaitTime());

        $limit = 5;
        $sender = new Sender($mockClient, $limit);
        $this->assertEquals(200000, $sender->calcWaitTime());
    }
}