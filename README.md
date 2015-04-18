# sms
Simple SMS Api for sending short text messages from your Laravel Application

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
<pre><code> Sms::send('sms.test','87686655455',['param1'=>'Name 1']);  </code></pre>
