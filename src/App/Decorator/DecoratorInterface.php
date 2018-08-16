<?php 

namespace App\Decorator;

/**
 * Define Decorator method requirements
 */
interface DecoratorInterface 
{
    /**
     * Convert the given data for a decorated data
     * @param mixed $data 
     */
    public function decorate($data);

    /**
     * Revert the decorated data to the original
     * @return array
     */
    public function revert($data);
}