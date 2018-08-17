<?php 

use App\Receiver;
use App\Decorator\DecoratorInterface;
use App\Storage\StorageInterface;

class ReceiverTest extends \PHPUnit\Framework\TestCase
{
    public function testSplitString()
    {
        $message = 'LoremLoremLorem';
        $part = 'Lorem';

        $mockDecor = $this->getMockBuilder(DecoratorInterface::class)
                   ->disableOriginalConstructor()
                   ->getMock();

        $mockStorage = $this->getMockBuilder(StorageInterface::class)
                   ->disableOriginalConstructor()
                   ->getMock();

        $receiver = new Receiver([], $mockStorage, $mockDecor);
        
        $limitChar = 5;
        foreach($receiver->split_message($message, $limitChar) as $messagePart) {
            $this->assertEquals($part, $messagePart['body']);
        }
    }

    public function testPostMessage()
    {

        $mockDecor = $this->getMockBuilder(DecoratorInterface::class)
                          ->disableOriginalConstructor()
                          ->getMock(); 

        $mockStorage = $this->getMockBuilder(StorageInterface::class)
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $mockStorage->expects($this->exactly(2))
                    ->method('add')
                    ->willReturn(true);

        $receiver = new Receiver(['message-max-size' => 5], $mockStorage, $mockDecor);
        $response = $receiver->post([mt_rand()], 'LoremLorem');
        
        $this->assertEquals('Message queued, thanks!', $response);
    }

    public function testPostExceptionMessage()
    {

        $mockDecor = $this->getMockBuilder(DecoratorInterface::class)
                          ->disableOriginalConstructor()
                          ->getMock(); 

        $mockStorage = $this->getMockBuilder(StorageInterface::class)
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $mockStorage->expects($this->exactly(1))
                    ->method('add')
                    ->will($this->throwException(new Exception));

        $receiver = new Receiver(['message-max-size' => 5], $mockStorage, $mockDecor);
        $response = $receiver->post([mt_rand()], 'LoremLorem');
        
        $this->assertEquals('Error: ', $response);
    }
}