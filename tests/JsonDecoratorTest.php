<?php 

use App\Decorator\JsonDecorator;
use PHPUnit\Framework\TestCase;

class JsonDecoratorTest extends TestCase
{
    public function testGenerateValidJsonMessage()
    {
        $originalMessage = 'Lorem';
        $decor = new JsonDecorator();
        $json = $decor->decorate($originalMessage);
        
        json_decode($json);

        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
    }

    public function testRevertCorrectMessage()
    {
        $originalMessage = 'Lorem';

        $decor = new JsonDecorator();
        $json = $decor->decorate($originalMessage);

        $this->assertNotEquals($json, $originalMessage);

        $returnMessage = $decor->revert($json);

        $this->assertEquals($returnMessage, $originalMessage);
    }
}