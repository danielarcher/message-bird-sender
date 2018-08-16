<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Decorator\LibSodiumDecorator;
use App\Storage\FileStorage;
use App\Sender;
use App\Udh;

$config = require __DIR__ . '/../config/' . getenv('APPLICATION_ENV') . '.php';

$config['public-key'] = file_get_contents($config['public-key-file']);

function split_message($messageBody) {
    
    $splitedMessage = str_split($messageBody,153);
    $total = count($splitedMessage);
    
    foreach($splitedMessage as $key => $part)
    {
        yield [
            'type' => 'binary',
            'typeDetails' => [
                'udh' => (new Udh($total, ++$key))->toString(), 
            ],
            'body' => $part
        ];
    }
}

$messageText = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

$storage = new FileStorage($config['data-file'], new LibSodiumDecorator($config['public-key']));
foreach(split_message($messageText) as $messagePart) {
    $storage->add([
        'originator'  => $config['originator'],
        'recipients'  => [31612345678], 
        'body'        => $messagePart['body'],
        'type'        => $messagePart['type'],
        'typeDetails' => $messagePart['typeDetails'],
    ]);
}

