<?php 

use App\Udh;
use PHPUnit\Framework\TestCase;

class UdhTest extends TestCase
{
    public function testPadValues()
    {
        $array = ['test' => '1'];
        $udh = new Udh(1,1);
        
        $paddedArray = $udh->getPaddedValues($array);

        $this->assertEquals(['test' => '01'], $paddedArray);
    }
}