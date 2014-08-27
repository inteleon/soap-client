# Inteleon Soap Client

Extends the native PHP Soap Client with some more options.

The usage is the same as the native SoapClient class.

## Installation (Composer)

Add this to your composer.json:

```json
"require": {
    "inteleon/inteleon-soap-client": "*"
}
```

Then run `composer install` inside that folder

Then, in your code, just use the composer autoloader:

## Set options

### Timeouts (milliseconds)

```php
$client->setTimeout(30000); //The maximum number of milliseconds to execute.
$client->setConnectTimeout(30000); //The number of milliseconds to wait while trying to connect.
```

### Connect attempts

```php
$client->setConnectAttempts(2); //Number of connect attempts of connection fails
```

### Verify SSL certificate

```php
$client->setVerifyCertificate(true); //Verify the SSL certificate. WARNING: Turning off CURLOPT_SSL_VERIFYPEER allows man in the middle (MITM) attacks, which you don't want!
```

### HTTP user agent

```php
$client->setUserAgent('Foo');
```

### Custom cURL option

```php
$client->setCurlOption(CURLOPT_XXX, ''); //See http://php.net/manual/en/function.curl-setopt.php
```

## Todo

- Ability to set timeouts/options on the WSDL fetching. As the native SoapClient seems to do this in the constructor it is hard to overwrite it.
- More and better tests
