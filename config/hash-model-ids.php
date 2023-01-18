<?php

return [
    'salt' => env('HASH_MODEL_IDS_SALT', hash('sha256', env('APP_KEY'))),
    'min_hash_length' => 10,
    'alphabet' => 'abcdefghijklmnopqrstuvwxyz0123456789'
];
