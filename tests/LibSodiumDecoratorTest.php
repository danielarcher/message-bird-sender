<?php 

use App\Decorator\LibSodiumDecorator;
use PHPUnit\Framework\TestCase;

class LibSodiumDecoratorTest extends TestCase
{
    public function testEncryptMessage()
    {
        $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $originalMessage = 'Lorem';

        $decor = new LibSodiumDecorator(bin2hex($key), bin2hex($nonce));
        $encryptedMessage = $decor->decorate($originalMessage);

        $this->assertNotEquals($encryptedMessage, $originalMessage);
    }

    public function testDecryptMessage()
    {
        $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $originalMessage = 'Lorem';

        $decor = new LibSodiumDecorator(bin2hex($key), bin2hex($nonce));
        $encryptedMessage = $decor->decorate($originalMessage);

        $this->assertNotEquals($encryptedMessage, $originalMessage);

        $returnMessage = $decor->revert($encryptedMessage);

        $this->assertEquals($returnMessage, $originalMessage);
    }
}