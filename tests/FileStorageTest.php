<?php 

use App\DecoratorInterface;
use App\FileStorage;
use App\JsonDecorator;

class FileStorageTest extends \PHPUnit\Framework\TestCase
{
    public function testAdd()
    {
        $decor = $this->getDecoratorMock();

        $fo = mt_rand();
        $fstorage = new FileStorage($fo, $decor);

        $this->assertTrue($fstorage->add(mt_rand()));
        unlink($fo);
    }

    public function testGet()
    {
        $string = 'abcd';
        
        $decor = $this->getDecoratorMock();

        $fo = mt_rand();
        $fstorage = new FileStorage($fo, $decor);

        $this->assertTrue($fstorage->add($string));
        $this->assertEquals($fstorage->get(), $string);
        unlink($fo);
    }

    public function testAddManyLines()
    {
        $decor = $this->getDecoratorMock();

        $fo = mt_rand();
        $fstorage = new FileStorage($fo, new Class implements DecoratorInterface {
            public function decorate($data) {
                return $data;
            }

            public function revert($data) {
                return $data;
            }
        });

        $fstorage->add('a');
        $fstorage->add('b');
        $fstorage->add('c');
        
        $this->assertEquals('a', $fstorage->get());
        $this->assertEquals('b', $fstorage->get());
        $this->assertEquals('c', $fstorage->get());
        
    }

    private function getDecoratorMock()
    {
        $string = 'abcd';

        $decor = $this->getMockBuilder(DecoratorInterface::class)
                      ->setMethods(['decorate', 'revert'])
                      ->enableArgumentCloning()
                      ->getMock();
        
        $decor->method('decorate')->willReturn($string . 'x');
        $decor->method('revert')->willReturn($string);

        return $decor;
    }
}