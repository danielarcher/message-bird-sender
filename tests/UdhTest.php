<?php 

use App\Udh;
use PHPUnit\Framework\TestCase;

class UdhTest extends TestCase
{
    public function testPadValues()
    {
        $array = ['test' => '1'];
        $udh = new Udh(1,1, mt_rand(1,255));
        
        $paddedArray = $udh->getPaddedValues($array);

        $this->assertEquals(['test' => '01'], $paddedArray);
    }

    public function testConverStringPaddedMethod()
    {
        $ref = mt_rand(1,14);
        $udh = new Udh(2,1, $ref);

        $expectedString = '050003' . '0' . dechex($ref) . '0201';
        
        $this->assertEquals($expectedString, $udh->toString());
    }

    public function testConverStringMethod()
    {
        $ref = mt_rand(16,255);
        $udh = new Udh(2,1, $ref);

        $expectedString = '050003' . dechex($ref) . '0201';
        
        $this->assertEquals($expectedString, $udh->toString());
    }
}