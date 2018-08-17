<?php 

namespace App;

/**
 * Logger class, to save message logs on file
 */
class Logger
{

    const DEBUG = 'debug';
    
    const INFO = 'info';
    
    const ERROR = 'error';
    
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function info($message)
    {
        $this->log($message, self::INFO);
    }

    public function debug($message)
    {
        $this->log($message, self::DEBUG);
    }

    public function error($message)
    {
        $this->log($message, self::ERROR);
    }

    public function log(string $message, string $level)
    {
        file_put_contents($this->filePath, $level . ':' . $message . "\n", FILE_APPEND);
    }
}