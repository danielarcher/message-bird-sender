<?php 

namespace App;

/**
 * Storage class, responsible for save the received data and return then when asked
 */
class Storage {

    /**
     * Queue of storaged data
     * @var array
     */
    private $queue = array();
    
    /**
     * Add new itens on the queue
     * @param array $data 
     */
    public function add(array $data): void {
        array_push($this->queue, json_encode($data));
    }

    /**
     * Remove the last inserted item from the queue
     * @return array
     */
    public function get(): array {
        return json_decode(array_shift($this->queue), true);
    }
}