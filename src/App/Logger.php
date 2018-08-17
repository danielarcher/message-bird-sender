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

    /**
     * Send a info log
     * @param  string $message 
     * @return void
     */
    public function info(string $message)
    {
        $this->log($message, self::INFO);
    }

    /**
     * Send debug log
     * @param  string $message 
     * @return void
     */
    public function debug(string $message)
    {
        $this->log($message, self::DEBUG);
    }

    /**
     * Send error log
     * @param  string $message 
     * @return void
     */
    public function error(string $message, $code)
    {
        $this->log('[code:' . $code . ']' . $message, self::ERROR);
    }

    /**
     * Save the loged message to a file
     * @param  string $message 
     * @param  string $level   
     * @return void
     */
    public function log(string $message, string $level)
    {
        $success = file_put_contents($this->filePath, $level . ':' . $message . "\n", FILE_APPEND);
        
        if (false == $success) {
            throw new \Exception("Error saving the log file, please verify the configuration path");
            
        }
    }
}