# sms
Simple SMS Driver for sending short text messages from your PHP Application. Facades for Laravel 5.

<strong>Installation</strong>

<ol>
  <li>Edit the composer.json add to the require array & run composer update<br>
      <pre><code> "softon/sms": "dev-master" </code></pre>
      <pre><code> composer update </code></pre>
  </li>
  <li>Add the service provider to the config/app.php file in Laravel<br>
      <pre><code> 'Softon\Sms\SmsServiceProvider', </code></pre>
      
  </li>
  <li>Add an alias for the Facade to the config/app.php file in Laravel<br>
      <pre><code> 'Sms' => 'Softon\Sms\Facades\Sms', </code></pre>
      
  </li>
  <li>Publish the config & views by running <br>
      <pre><code> php artisan vendor:publish </code></pre>
      
  </li>
</ol>


<strong>Usage</strong>

Edit the config/sms.php. Set the appropriate Gateway and its parameters. Then in your code... <br>
<pre><code> use Softon\Sms\Facades\Sms;  </code></pre>
Send Single SMS:-
<pre><code> Sms::send('9090909090','sms.test',['param1'=>'Name 1']);  </code></pre>
Send Multiple SMS:-
<pre><code> Sms::send(['87686655455','1212121212','2323232323'],'sms.test',['param1'=>'Name 1']);  </code></pre>
