# Inteleon Soap Client

Extends the native PHP Soap Client with some more options. The class uses cURL to make the requests. The usage is the same as the native SoapClient class.

## Installation

~~Run: `composer require inteleon/soap-client`~~

Package not registered on Packagist yet. Clone manually:

`git clone https://github.com/Inteleon/Soap-Client`

## Set options

### Timeouts (milliseconds)

```php
$client->setTimeout(30000); //The maximum number of milliseconds to execute.
$client->setConnectTimeout(30000); //The number of milliseconds to wait while trying to connect.
```

### Connect attempts

```php
$client->setConnectAttempts(2); //Number of connect attempts if connection fails
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
