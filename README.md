# SMS
Simple SMS(Short Messaging Service) Gateway Package for sending short text messages from your Application. Facade for Laravel 5(Updated to work with Laravel 5.*).Currently supported Gateways Clickatell, MVaayoo, Gupshup, SmsAchariya, SmsCountry, SmsLane, Nexmo, Mocker, MSG91 / Any HTTP/s based Gateways are supported by Custom Gateway. Log gateway can be used for testing.

<strong>Installation</strong>

<ol>
  <li>Edit the composer.json add to the require array & run composer update<br>
      <pre><code> composer require softon/sms </code></pre>
  </li>
  <li>(Optional for Laravel 5.5+) Add the service provider to the config/app.php file in Laravel<br>
      <pre><code> Softon\Sms\SmsServiceProvider::class, </code></pre>
      
  </li>
  <li>(Optional for Laravel 5.5) Add an alias for the Facade to the config/app.php file in Laravel<br>
      <pre><code> 'Sms' => Softon\Sms\Facades\Sms::class, </code></pre>
      
  </li>
  <li>Publish the config & views by running <br>
      <pre><code> php artisan vendor:publish --provider="Softon\Sms\SmsServiceProvider" </code></pre>
      
  </li>
</ol>


<strong>Usage</strong>

Edit the config/sms.php. Set the appropriate Gateway and its parameters. Then in your code... <br>
Put your blade template for the SMS in the resources/views/sms folder. Then use the below lines of code to send SMS. 
```php
use Softon\Sms\Facades\Sms;  
 ```
Send Single SMS with View:-
```php
// Params: [MobileNumber,Blade View Location,SMS Params If Required]
Sms::send('9090909090','sms.test',['param1'=>'Name 1']);  
 ```
 Send Single SMS with Raw Message:-
```php
// Params: [MobileNumber,Blade View Location,SMS Params If Required]
Sms::send('9090909090','Any Message Text To be sent.');  
 ```
Send Multiple SMS:-
```php
// Params: [Array of MobileNumbers,Blade View Location,SMS Params If Required]
Sms::send(['87686655455','1212121212','2323232323'],'sms.test',['param1'=>'Name 1']);  
 ```
Select the Gateway before sending the Message:-
```php
/*****************************************************
 Gateways ::  log / clickatell / gupshup / mvaayoo / 
              smsachariya / smscountry / smslane / 
              nexmo / msg91 / mocker / custom 
*****************************************************/

Sms::gateway('mocker')->send(['87686655455','1212121212','2323232323'],'sms.test',['param1'=>'Name 1']);  
```

With Response:-
```php 
// This command gives you the reply recieved from the server.
Sms::send(['87686655455','1212121212','2323232323'],'sms.test',['param1'=>'Name 1'])->response();  
```


<strong>Custom Gateway</strong>
Let us suppose you want to use any other gateway. Find the API url with which sms can be sent.
For Example : <code>http://example.com/api/sms.php?uid=737262316a&pin=YOURPIN&sender=your_sender_id&route=0&mobile=8888888888&message=How are You&pushid=1</code>

Then you can setup the Config of Custom Gateway like this:

```php 
        'custom' => [                           // Can be used for any gateway
            'url' => '',                        // Gateway Endpoint
            'params' => [                       // Parameters to be included in the request
                'send_to_name' => 'mobile',           // Name of the field of recipient number
                'msg_name' => 'message',               // Name of the field of Message Text
                'others' => [                   // Other Authentication params with their values
                    'uid' => '737262316a',
                    'pin' => 'YOURPIN',
                    'sender' => 'your_sender_id',
                    'route' => '0',
                    'pushid' => '1',
                ],
            ],
            'add_code' => true,                 // Append country code to the mobile numbers
        ],
```
