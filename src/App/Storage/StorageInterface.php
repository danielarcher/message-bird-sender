<?php 

namespace App\Storage;

use App\Decorator\DecoratorInterface;

/**
 * Storage class, responsible for save the received data and return then when asked
 */
interface StorageInterface
{
    public function setDecorator(DecoratorInterface $decorator);
    /**
     * Add new itens on the storage
     * @param array $data 
     */
    public function add($data): bool;

    /**
     * Remove the last inserted from the storage
     * @return array
     */
    public function get();
}