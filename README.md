# khalyomede/systempay

Systempay toolbox for PHP applications.

![Packagist Version](https://img.shields.io/packagist/v/khalyomede/systempay) [![Build Status](https://travis-ci.com/khalyomede/systempay.svg?branch=master)](https://travis-ci.com/khalyomede/systempay) [![Maintainability](https://api.codeclimate.com/v1/badges/a4ed574db718472ee6d0/maintainability)](https://codeclimate.com/github/khalyomede/systempay/maintainability) ![Packagist License](https://img.shields.io/packagist/l/khalyomede/systempay) ![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/khalyomede/systempay)

## Summary

- [About](#about)
- [Requirements](#requirements)
- [Installation](#installation)
- [Examples](#examples)
- [API](#api)
- [Run the tests](#run-the-tests)
- [Compatibility table](#compatibility-table)

## About

I created this package to be able to use it in my Laravel shop application.

I have seen the packages of others folks, but I either did not found every tools I need, or the tools were untested.

I hope to provide folks with a tested library so you can use it with confidence.

This package respects the [Semantic versioning](https://semver.org/).

## Requirements

- Composer
- PHP version 7.1+ (corresponds to Laravel 5.6+)

## Installation

In the root of your folder project, run this command:

```bash
composer require khalyomede/systempay:0.*
```

## Examples

- [1. Basic example](#1-basic-example)

### 1. Basic example

In this example, we will only fill mandatories fields before generating the hidden HTML inputs to inject in an HTML page.

```php
<?php

use Khalyomede\Systempay\Payment;
use Khalyomede\Systempay\Currency;
use Khalyomede\Systempay\ContextMode;
use Khalyomede\Systempay\HashAlgorithm;
use Khalyomede\Systempay\PaymentConfiguration;

$payment = new Payment;
$payment->setKey("foo")
    ->setSiteId("12345678")
    ->setTotalAmount(199.99)
    ->setContextMode(ContextMode::TEST)
    ->setCurrency(Currency::EUR)
    ->setPaymentConfiguration(PaymentConfiguration::SINGLE) // One shot payment
    ->setTransactionDate(new DateTime("NOW"))
    ->setTransactionId("xrT15p")
    ->setHashAlgorithm(HashAlgorithm::SHA256);

$fields = $payment->getHtmlFormFields();
$url = $payment->getFormUrl();

?>

<form method="POST" action="<?= $url ?>">
  <?= $fields ?>
  <button type="submit">Payer</button>
</form>
```

## API

- `Khalyomede\Systempay\Payment::class`
  - [`Payment::__construct`](#payment::__construct)
  - [`Payment::getHashAlgorithm`](#payment::gethashalgorithm)
  - [`Payment::getTotalAmount`](#payment::gettotalamount)
  - [`Payment::getFormTotalAmount`](#payment::getformtotalamount)
  - [`Payment::getSiteId`](#payment::getsiteid)
  - [`Payment::getContextMode`](#payment::getcontextmode)
  - [`Payment::getCurrencyNumericCode`](#payment::getcurrencynumericcode)
  - [`Payment::getPaymentConfiguration`](#payment::getpaymentconfiguration)
  - [`Payment::getTransactionDate`](#payment::gettransactiondate)
  - [`Payment::getFormTransactionDate`](#payment::getformtransactiondate)
  - [`Payment::getTransactionId`](#payment::gettransactionid)
  - [`Payment::getVersion`](#payment::getversion)
  - [`Payment::getActionMode`](#payment::getactionmode)
  - [`Payment::getPageAction`](#payment::getpageaction)
  - [`Payment::getHtmlFormFields`](#payment::gethtmlformfields)
  - [`Payment::getKey`](#payment::getkey)
  - [`Payment::getFormUrl`](#payment::getformurl)
  - [`Payment::setHashAlgorithm`](#payment::sethashalgorithm)
  - [`Payment::setTotalAmount`](#payment::settotalamount)
  - [`Payment::setSiteId`](#payment::setsiteid)
  - [`Payment::setContextMode`](#payment::setcontextmode)
  - [`Payment::setCurrency`](#payment::setcurrency)
  - [`Payment::setPaymentConfiguration`](#payment::setpaymentconfiguration)
  - [`Payment::setTransactionDate`](#payment::settransactiondate)
  - [`Payment::setTransactionId`](#payment::setTransactionId)
- `Khalyomede\Systempay\ContextMode::class`
  - [`ContextMode::__construct`](#contextmode::__construct)
  - [`ContextMode::isAllowed`](#contextmode::isallowed)
  - [`ContextMode::getAllowedToString`](#contextmode::getallowedtostring)
- `Khalyomede\Systempay\HashAlgorithm::class`
  - [`HashAlgorithm::__construct`](#hashalgorithm::__construct)
  - [`HashAlgorithm::isSupported`](#hashalgorithm::isSupported)
  - [`HashAlgorithm::isAllowed`](#hashalgorithm::isallowed)
  - [`HashAlgorithm::getAllowedToString`](#hashalgorithm::getallowedtostring)
- `Khalyomede\Systempay\PaymentConfiguration::class`
  - [`PaymentConfiguration::__construct`](#paymentconfiguration::__construct)
  - [`PaymentConfiguration::isAllowed`](#paymentconfiguration::isallowed)
  - [`PaymentConfiguration::getAllowedToString`](#paymentconfiguration::getallowedtostring)

### `Payment::__construct`

The constructor will automatically fill the following data:

- Currency to "EUR"
- Payment configuration to "SINGLE"
- The transaction date to now
- A random secure transaction id
- The context mode to "TEST"

### `Payment::getHashAlgorithm`

Get the hash algorithm.

```php
public function getHashAlgorithm(): string;
```

### `Payment::getTotalAmount`

Get the total amount.

```php
public function getTotalAmount(): float;
```

### `Payment::getFormTotalAmount`

Get the total amount, formatted to fit Systempay requirements (e.g., no decimal separators). For example, if the amount is 199.99, the value returned by this method will be 19999.

```php
public function getFormTotalAmount(): int;
```

### `Payment::getSiteId`

Get the site id. Check the Systempay documentation to know where to find your site id.

```php
public function getSiteId(): string;
```

### `Payment::getContextMode`

Get the context mode.

```php
public function getContextMode(): string;
```

### `Payment::getCurrencyNumericCode`

Get the 3 digits numeric code of the currency.

```php
public function getCurrencyNumericCode(): int;
```

### `Payment::getPaymentConfiguration`

Get the payment configuration.

```php
public function getPaymentConfiguration(): string;
```

### `Payment::getTransactionDate`

Get the transaction date.

```php
public function getTransactionDate(): DateTime;
```

### `Payment::getFormTransactionDate`

Get the transaction date formatted for the form. It is formatted with the DateTime format "YmdHis" according to the Systempay transaction date format requirement.

```php
public function getFormTransactionDate(): string;
```

### `Payment::getTransactionId`

Get the transaction id.

```php
public function getTransactionId(): string;
```

### `Payment::getVersion`

Get the payment protocol version.

```php
public function getVersion(): string;
```

### `Payment::getActionMode`

Get the payment action mode.

```php
public function getActionMode(): string;
```

### `Payment::getPageAction`

Get the payment page paction.

```php
public function getPageAction(): string;
```

### `Payment::getHtmlFormFields`

Get the html form fields that corresponds to your payment. Each fields is an `<input type="hidden" />`.

```php
public function getHtmlFormFields(): string;
```

### `Payment::getKey`

Get your site key. Check the Systempay documentation to know where to find your site key.

```php
public function getKey(): string;
```

### `Payment::getFormUrl`

Get the form URL.

```php
public function getFormUrl(): string;
```

### `Payment::setHashAlgorithm`

```php
public function setHashAlgorithm(string $algorithm): Payment;
```

Set the hash algorithm between sha1 and (hmac) sha256.

**throws**

- `InvalidArgumentException`: If the hash algorithm is not supported by the machine that runs the script.
- `InvalidArgumentException`: If the hash algorithm is not one of "SHA1" or "SHA256".

### `Payment::setTotalAmount`

Set the total amount of the payment.

```php
public function setTotalAmount(float $amount): Payment;
```

### `Payment::setSiteId`

```php
public function setSiteId(string $siteId): Payment;
```

Set the site id (check the Systempay documentation to know where to find your site id).

**throws**

- `InvalidArgumentException`: If the provided site id exceed 8 characters.
- `InvalidArgumentException`: If the provided site id is not a valid UTF-8 string.

### `Payment::setContextMode`

```php
public function setContextMode(string $mode): Payment;
```

Set the context mode (either "TEST" or "PRODUCTION"). You can use the `ContextMode` class constants to avoid hard writing the mode.

**throws**

- `InvalidArgumentException`: If the context mode is not one of "TEST" or "PRODUCTION".

### `Payment::setCurrency`

Set the currency using the alpha-3 currency code (like "EUR"). You can use the `Currency` class constants to avoid hard writing the currency.

```php
public function setCurrency(string $currency): Payment;
```

**throws**

- `InvalidArgumentException`: If the currency is not a valid ISO4217 currency.

### `Payment::setPaymentConfiguration`

Set the payment configuration (either "SINGLE" or "MULTI"). You can use the `PaymentConfiguration` class constants to avoid hard writing the configuration.

```php
public function setPaymentConfiguration(string $configuration): Payment;
```

**throws**

- `InvalidArgumentException`: If the payment configuration is not one of "SINGLE" or "MULTI".

### `Payment::setTransactionDate`

Set the transaction date.

```php
public function setTransactionDate(DateTime $date): Payment;
```

### `Payment::setTransactionId`

Set the transaction id.

```php
public function setTransactionId(string $transactionId): Payment;
```

**throws**

- `InvalidArgumentException`: If the transaction id is not 6 characters long.
- `InvalidArgumentException`: If the transaction id is not a valid UTF-8 string.

### `Payment::setKey`

Set the key, that is used to generate the signature and validating the authenticity of the request.

```php
public function setKey(string $key): Payment;
```

### `ContextMode::__construct`

Constructor the context mode with the given mode.

```php
public function __construct(string $mode);
```

### `ContextMode::isAllowed

Returns true if the context mode is allowed, else returns false.

```php
public function isAllowed(): bool;
```

### `ContextMode::getAllowedToString

Get the allowed context mode in a string, separated by a coma.

```php
public static function getAllowedToString(): string;
```

### `HashAlgorithm::__construct`

Construct with the given algorithm.

```php
public function __construct(string $algorithm);
```

### `HashAlgorithm::isSupported`

Return true if the algorithm is supported by the machine running the current script, else return false.

```php
public function isSupported(): bool;
```

### `HashAlgorithm::isAllowed`

Return true if the algorithm is either SHA1 or SHA256, else returns false.

```php
public function isAllowed(): bool;
```

### `HashAlgorithm::getAllowedToString`

Get the allowed algorithm as a string separated by a coma.

```php
public static function getAllowedToString(): string;
```

### `PaymentConfiguration::__construct`

Construct with the given payment configuration.

```php
public function __construct(string $configuration);
```

### `PaymentConfiguration::isAllowed`

Returns true if the payment configuration is allowed, else return false.

```php
public function isAllowed(): bool;
```

### `PaymentConfiguration::getAllowedToString`

Returns the allowed payment configuration as a string separated by a coma.

```php
public static function getAllowedToString(): string;
```

## Run the tests

Execute this command in the root folder of this project:

```bash
composer run test
```

## Compatibility table

_?: Untested_

|     | 7.1  | 7.2  | 7.3  | 7.4  | 8.0 |
| --- | ---- | ---- | ---- | ---- | --- |
| v0  | pass | pass | pass | pass | ?   |

You can counter check these results by following this procedure:

1. Checkout to the desired branch: `git checkout v1.2.3`
2. Start the Docker containers: `docker-compose up -d`
3. In the file `docker-compose.yml`, change the version of PHP in the `build` key of the `php` service with the one that fits your need
4. Run the tests: `docker-compose exec php composer run test`
