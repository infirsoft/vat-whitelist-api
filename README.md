# API Rejestr WL

White List API / API Wykazu podatników VAT / Biała lista podatników VAT

### API Docs
* https://wl-api.mf.gov.pl/
* https://www.gov.pl/web/kas/api-wykazu-podatnikow-vat

### Methods
```bash
searchNip( string $nip , string $date ) : EntityResponse
searchNips( array $nips , string $date ) : EntityListResponse
searchRegon( string $regon , string $date ) : EntityResponse
searchRegons( array $regons , string $date ) : EntityListResponse
searchBankAccount( string $bankAccount , string $date ) : EntityListResponse
searchBankAccounts( array $bankAccounts , string $date ) : EntityListResponse
checkNipBankAccount( string $nip , string $bankAccount , string $date ) : EntityCheckResponse
checkRegonBankAccount( string $regon , string $bankAccount , string $date ) : EntityCheckResponse
```

### Usage
```php
$client = new WhiteListApiClient;
$response = $client->searchNip('5252344078', '2021-01-01');
echo $response->result->subject->name; // GOOGLE POLAND SPÓŁKA Z OGRANICZONĄ ODPOWIEDZIALNOŚCIĄ
```