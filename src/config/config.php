<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sms Service Config
    |--------------------------------------------------------------------------
    |   gateway = Gupshup / MVaayoo
    |   view    = File
    */



    'gateway' => 'Gupshup',


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
