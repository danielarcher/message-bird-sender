<?php 

return [
	'api-key'                   => '__your_key_here__',
    'originator'                => 'MessageBird',
    'message-max-size'          => 160,
    'data-file'                 => __DIR__ . '/../data/messages_to_send.txt',
    'log-file'                  => __DIR__ . '/../logs/app.log',
    'limit-messages-per-second' => 1,
    'queue-id'                  => 8756342,
    'sodium-key'                => 'a4018eb2093323aacd6055e84a9f4f16e2fb0041e91a1fed075c59441ac92196',
    'sodium-nonce'              => 'b8234623cb15f9c68d47c6d36d191de39e8ab1f9d2238926'
];