<?php 

namespace App\Decorator;

/**
 * Json decorator transform all data in json encoded string and revert as an array
 */
class JsonDecorator implements DecoratorInterface
{
    /**
     * Convert new itens in json encoded string
     * @param mixed $data 
     */
    public function decorate($data)
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Revert the json encoded string to an array
     * @return array
     */
    public function revert($data)
    {
        return json_decode($data, true);
    }
}