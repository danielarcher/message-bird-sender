<?php 

use App\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    public function testLog()
    {
        $file = mt_rand();

        $logger = new Logger($file);
        $logger->log('hello','level');

        $result = file_get_contents($file);

        $this->assertEquals("level:hello\n", $result);

        unlink($file);
    }

    public function testInfoLog()
    {
        $file = mt_rand();

        $logger = new Logger($file);
        $logger->info('hello');

        $result = file_get_contents($file);

        $this->assertEquals("info:hello\n", $result);

        unlink($file);
    }

    public function testDebugLog()
    {
        $file = mt_rand();

        $logger = new Logger($file);
        $logger->debug('hello');

        $result = file_get_contents($file);

        $this->assertEquals("debug:hello\n", $result);

        unlink($file);
    }

    public function testErrorLog()
    {
        $file = mt_rand();

        $logger = new Logger($file);
        $logger->error('hello', 3);

        $result = file_get_contents($file);

        $this->assertEquals("error:[code:3]hello\n", $result);

        unlink($file);
    }
}