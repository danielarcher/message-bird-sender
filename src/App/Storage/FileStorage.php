<?php 

namespace App\Storage;

use App\Decorator\DecoratorInterface;

/**
 * Storage class, responsible for save the received data and return then when asked
 */
class FileStorage implements StorageInterface
{
    public function __construct(string $filePath, DecoratorInterface $decorator)
    {
        $this->filePath = $filePath;
        $this->decorator = $decorator;
    }

    /**
     * Add new itens on the file
     * @param array $data 
     */
    public function add($data): bool
    {
        return (bool) file_put_contents($this->filePath, $this->decorator->decorate($data) . "\n", FILE_APPEND);
    }

    /**
     * Remove the last inserted item from the file
     * @return array
     */
    public function get()
    {
        $arrayFile = file($this->filePath);

        if (empty($arrayFile)) {
            return false;
        }
        
        $firstLine = array_shift($arrayFile);

        $this->save($arrayFile);

        return $this->decorator->revert($firstLine);
    }

    /**
     * Save the new arrayData on file
     * @param  array  $arrayData [description]
     * @return [type]            [description]
     */
    public function save(array $arrayData)
    {
        file_put_contents($this->filePath, "");
        foreach ($arrayData as $data) {
            print_r($data);
            $this->add($data);
        }
    }
}