<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sms Service Config
    |--------------------------------------------------------------------------
    |   gateway = Mocker / Log / Clickatell / Gupshup / MVaayoo / 
                  SmsAchariya / SmsCountry / SmsLane / 
                  Nexmo / MSG91 / Custom
    |   view    = File
    */

    'countryCode' => '+91',

    'gateway' => 'Mocker',                     // Replace with the name of appropriate gateway


    'view'    => 'File',

    /**
     * Mock your SMSes with Mocker.in
     * Select Any Random Value for the Sender ID and then check messages at
     * https://mocker.in/sms/YOUR_SENDER_ID
     * e.g.: https://mocker.in/sms/MOCKER will list all messages sent to this id.
     */

    'mocker' => [
        'sender_id'  => 'MOCKER',
    ],

    'clickatell' => [                       // Get it from http://clickatell.com
        'api_id'  => '',
        'user'  => '',
        'password' => '',
    ],

    'gupshup' => [
        'userid'  => '',                    // Get it from http://enterprise.gupshup.com
        'password' => '',
    ],

    'itexmo' => [
        'api_code'  => '',                 // API_CODE
    ],

    'mvaayoo' => [
        'user'  => '',                      // Get it from http://mvaayoo.com
        'senderID'  => 'TEST SMS',
    ],


    'smsachariya' => [
        'domain'  => '',                    // Get it From http://smsachariya.com
        'uid'  => '',
        'pin'  => '',
    ],

    'smscountry' => [                       // Get it from http://www.smscountry.com/
        'user'  => '',
        'passwd'  => '',
        'sid'  => 'SMSCountry',
    ],

    'smslane' => [                          // Get it from http://smslane.com
        'user'  => '',
        'password'  => '',
        'sid'  => 'WebSMS',
        'gwid'  => '1',                     // 1 - Promotional & 2 - Transactional Route
    ],

    'nexmo' => [
        'api_key'  => '',                   // Get it From http://nexmo.com
        'api_secret'  => '',
        'from'  => '',
    ],


    'msg91' => [
        'authkey'  => '',                   // http://msg91.com
        'sender'  => '',
        'route'  => '4',                    // 1 = Promotional, 4 = Transactional
        'country'  => '91',
    ],

    'custom' => [                           // Can be used for any gateway
        'url' => '',                        // Gateway Endpoint
        'params' => [                       // Parameters to be included in the request
            'send_to_name' => '',           // Name of the field of recipient number
            'msg_name' => '',               // Name of the field of Message Text
            'others' => [                   // Other Authentication params with their values
                'param1' => '',
                'param2' => '',
                'param3' => '',
                'param4' => '',
            ],
        ],
        'add_code' => true,                 // Append country code to the mobile numbers
    ],

    /*
     * Example of Custom Gateway
     * Actual Url : http://example.com/api/sms.php?uid=737262316a&pin=YOURPIN&sender=your_sender_id&route=0&mobile=MOBILE&message=MESSAGE&pushid=1
     * 'custom' => [                           // Can be used for any gateway
            'url' => 'http://example.com/api/sms.php?',
            'params' => [
                'send_to_name' => 'mobile',
                'msg_name' => 'message',
                'others' => [
                    'uid' => '737262316a',
                    'pin' => 'YOURPIN',
                    'sender' => 'your_sender_id',
                    'route' => '0',
                    'pushid' => '1',
                ],
            ],
            'add_code' => true,
        ],
     */


];
