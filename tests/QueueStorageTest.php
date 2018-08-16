<?php 

use App\Decorator\DecoratorInterface;
use App\Storage\QueueStorage;

class QueueStorageTest extends \PHPUnit\Framework\TestCase
{
    public function testAddMessageInQueue()
    {
        $storage = new QueueStorage(mt_rand());

        $this->assertTrue($storage->add('mytext'));
        $return = $storage->add('mytext');
    }

    public function testTotalMessageInQueue()
    {
        $storage = new QueueStorage(mt_rand());
        $storage->add('mytext');
        $storage->add('mytext');

        $this->assertEquals(2, $storage->getTotal());
    }

    public function testRetrieveMessageFromQueue()
    {
        $storage = new QueueStorage(mt_rand());
        $storage->add('mytext1');
        $storage->add('mytext2');

        $this->assertEquals('mytext1', $storage->get());
        $this->assertEquals('mytext2', $storage->get());
        $this->assertEquals(false, $storage->get());
    }

    public function testRetrieveMessageFromQueueWithDecorator()
    {
        $storage = new QueueStorage(mt_rand());
        $storage->setDecorator( new Class implements DecoratorInterface {
            public function decorate($data) {
                return $data.'XXX';
            }

            public function revert($data) {
                return $data; # verify if the decorated string will be returned
            }
        });

        $storage->add('mytext1');
        $this->assertEquals('mytext1XXX', $storage->get());
        
    }
}