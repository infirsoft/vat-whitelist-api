API Rejestr WL
==============

[![License](https://img.shields.io/packagist/l/lozynskiadam/whitelistapi?color=blue)](./LICENSE.md)
[![Version](https://img.shields.io/packagist/v/lozynskiadam/whitelistapi?color=blue)](https://packagist.org/packages/lozynskiadam/whitelistapi)
[![Downloads](https://img.shields.io/packagist/dt/lozynskiadam/whitelistapi?color=blue)](https://packagist.org/packages/lozynskiadam/whitelistapi)
[![StyleCI](https://github.styleci.io/repos/338450280/shield?style=flat&branch=main)](https://github.styleci.io/repos/338450280?branch=main)

White List API / API Wykazu podatników VAT / Biała lista podatników VAT

API Docs
--------

* https://wl-api.mf.gov.pl/
* https://www.gov.pl/web/kas/api-wykazu-podatnikow-vat

Installation
------------

```bash
composer require lozynskiadam/whitelistapi
```

Supported Versions
------------------

| Version | PHP version | HTTP client |
|---------|-------------|-------------|
| 2.x     | \>= 8.0     | cURL        |
| 1.x     | \>= 7.1     | cURL        |

Methods
------------------

```php
searchNip( string $nip , string $date ) : EntityResponse|Error
searchNips( array $nips , string $date ) : EntryListResponse|Error
searchRegon( string $regon , string $date ) : EntityResponse|Error
searchRegons( array $regons , string $date ) : EntryListResponse|Error
searchBankAccount( string $bankAccount , string $date ) : EntityListResponse|Error
searchBankAccounts( array $bankAccounts , string $date ) : EntryListResponse|Error
checkNipBankAccount( string $nip , string $bankAccount , string $date ) : EntityCheckResponse|Error
checkRegonBankAccount( string $regon , string $bankAccount , string $date ) : EntityCheckResponse|Error
```

Usage
------------------

```php
<?php

require_once './vendor/autoload.php';

$client = new \WhiteListApi\WhiteListApiClient;
$response = $client->searchNip('5252344078', '2021-01-01');
echo $response->result->subject->name; // GOOGLE POLAND SPÓŁKA Z OGRANICZONĄ ODPOWIEDZIALNOŚCIĄ
```
