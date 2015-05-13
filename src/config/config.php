<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sms Service Config
    |--------------------------------------------------------------------------
    |   gateway = Log / Gupshup / MVaayoo
    |   view    = File
    */



    'gateway' => 'Log',


    'view'    => 'File',


    'gupshup' => [
        'userid'  => env('SMS_USERID'),
        'password' => env('SMS_PASSWORD'),
    ],

    'mvaayoo' => [
        'user'  => 'test:test123',
        'senderID'  => '56263',
    ],

];
