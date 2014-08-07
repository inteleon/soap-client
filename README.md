# Inteleon Soap Client

Extends the native PHP Soap Client with some more options.

The usage is the same as the native SoapClient class.

## Instantiate

```php
$soap_client = new Inteleon\InteleonSoapClient($wsdl, $options);
```

## Timeouts

```php
$this->setTimeout(30000);
$this->setConnectTimeout(30000);
```

## Connect attempts

```php
$this->setConnectAttempts(2);
```
