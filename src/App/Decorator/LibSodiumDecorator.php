<?php 

namespace App\Decorator;

/**
 * Json decorator transform all data in json encoded string and revert as an array
 */
class LibSodiumDecorator implements DecoratorInterface
{
    private $publicKey;

    private $privateKey;

    public function __construct($publicKey, $privateKey = null)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }
    
    /**
     * Convert new itens in json encoded string
     * @param mixed $data 
     */
    public function decorate($data)
    {
        return sodium_crypto_box_seal(
            json_encode($data),
            $this->publicKey
        );
    }

    /**
     * Revert the json encoded string to an array
     * @return array
     */
    public function revert($data)
    {
        $decrypted = sodium_crypto_box_seal_open(
            $data,
            $this->privateKey
        );

        print_r($this->privateKey);
        
        print_r($decrypted);
        exit;

        return json_decode($decrypted, true);
    }
}