<?php 

use App\Controller\ReceiverController;

class ReceiverControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testSplitString()
    {
        $message = 'LoremLoremLorem';
        $part = 'Lorem';

        $controller = new ReceiverController([]);
        
        foreach($controller->split_message($message, 5) as $messagePart) {
            $this->assertEquals($part, $messagePart['body']);
        }
    }
}