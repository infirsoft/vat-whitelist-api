# API Rejestr WL

White List API / API Wykazu podatników VAT / Biała lista podatników VAT

### API Docs
* https://wl-api.mf.gov.pl/
* https://www.gov.pl/web/kas/api-wykazu-podatnikow-vat

### Methods
```bash
searchNip( string $nip , string $date ) : array
searchNips( array $nips , string $date ) : array
searchRegon( string $regon , string $date ) : array
searchRegons( array $regons , string $date ) : array
searchBankAccount( string $bankAccount , string $date ) : array
searchBankAccounts( array $bankAccounts , string $date ) : array
checkNipBankAccount( string $nip , string $bankAccount , string $date ) : array
checkRegonBankAccount( string $regon , string $bankAccount , string $date ) : array
getResponse( ) : array|null
getResponseHttpCode ( ) : int|null
```

### Usage
```php
<?php

require('WhiteListApi.php');

$wl = new WhiteListApi;
$wl->searchNip('5252344078', '2021-01-01');
print_r($wl->getResponse());
```