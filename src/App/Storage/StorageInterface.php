<?php 

namespace App\Storage;

/**
 * Storage class, responsible for save the received data and return then when asked
 */
interface StorageInterface
{
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