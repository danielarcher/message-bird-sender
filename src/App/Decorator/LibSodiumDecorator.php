<?php 

namespace App\Decorator;

/**
 * Json decorator transform all data in json encoded string and revert as an array
 */
class LibSodiumDecorator implements DecoratorInterface
{
    private $key;

    private $nonce;

    public function __construct(string $key, string $nonce)
    {
        $this->key = hex2bin($key);
        $this->nonce = hex2bin($nonce);
    }
    
    /**
     * Convert new itens in json encoded string
     * @param mixed $data 
     */
    public function decorate($data)
    {
        return sodium_crypto_secretbox(json_encode($data), $this->nonce, $this->key);
    }

    /**
     * Revert the json encoded string to an array
     * @return array
     */
    public function revert($data)
    {
        $converteData = sodium_crypto_secretbox_open($data, $this->nonce, $this->key);
        if ($converteData === false) {
            throw new Exception("Bad ciphertext");
        }

        return json_decode($converteData, true);
    }
}