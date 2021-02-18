<?php

use PHPUnit\Framework\TestCase;
use WhiteListApi\Contents\Entity;
use WhiteListApi\Contents\EntityCheck;
use WhiteListApi\Contents\EntityCheckResponse;
use WhiteListApi\Contents\EntityItem;
use WhiteListApi\Contents\EntityList;
use WhiteListApi\Contents\EntityListResponse;
use WhiteListApi\Contents\EntityResponse;
use WhiteListApi\Contents\Entry;
use WhiteListApi\Contents\EntryList;
use WhiteListApi\Contents\EntryListResponse;
use WhiteListApi\Contents\Error;
use WhiteListApi\WhiteListApiClient;

class WhiteListApiClientTest extends TestCase
{
    /** @var WhiteListApiClient */
    private static $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new WhiteListApiClient('TEST');
    }

    public function testSearchBankAccount_WhenEmptyDateGiven()
    {
        $response = self::$client->searchBankAccount('70506405335016096312945164', '');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-101', $response->code);
    }

    public function testSearchBankAccount_WhenWrongDateFormatGiven()
    {
        $response = self::$client->searchBankAccount('70506405335016096312945164', '01.01.2021');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testSearchBankAccount_WhenTooEarlyDateGiven()
    {
        $response = self::$client->searchBankAccount('70506405335016096312945164', '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testSearchBankAccount_WhenFutureDateGiven()
    {
        $response = self::$client->searchBankAccount('70506405335016096312945164', (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testSearchBankAccount_WhenBankAccountHasWrongLength()
    {
        $response = self::$client->searchBankAccount('0', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-109', $response->code);
    }

    public function testSearchBankAccount_WhenBankAccountContainsForbiddenCharacters()
    {
        $response = self::$client->searchBankAccount('ZZ000000000000000000000000', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-110', $response->code);
    }

    public function testSearchBankAccount_WhenBankAccountChecksumIsInvalid()
    {
        $response = self::$client->searchBankAccount('99000000000000000000000000', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-111', $response->code);
    }

    public function testSearchBankAccount_WhenBankAccountNotFound()
    {
        $response = self::$client->searchBankAccount('17249010574040965557335358', '2021-01-01');
        $this->assertInstanceOf(EntityListResponse::class, $response);

        $this->assertInstanceOf(EntityList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertSame([], $response->result->subjects);
    }

    public function testSearchBankAccount_WhenBankAccountFound()
    {
        $response = self::$client->searchBankAccount('70506405335016096312945164', '2021-01-01');
        $this->assertInstanceOf(EntityListResponse::class, $response);

        $this->assertInstanceOf(EntityList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entity::class, $response->result->subjects[0]);
        $this->assertSame('Nazwa Firmy 52', $response->result->subjects[0]->name);
        $this->assertSame('4258758047', $response->result->subjects[0]->nip);
    }

    public function testSearchBankAccounts_WhenEmptyBankAccountListGiven()
    {
        $response = self::$client->searchBankAccounts([], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-108', $response->result->entries[0]->error->code);
    }

    public function testSearchBankAccounts_WhenEmptyDateGiven()
    {
        $response = self::$client->searchBankAccounts(['70506405335016096312945164', '20028681823250598006154766'], '');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-101', $response->code);
    }

    public function testSearchBankAccounts_WhenWrongDateFormatGiven()
    {
        $response = self::$client->searchBankAccounts(['70506405335016096312945164', '20028681823250598006154766'], '01.01.2020');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testSearchBankAccounts_WhenTooEarlyDateGiven()
    {
        $response = self::$client->searchBankAccounts(['70506405335016096312945164', '20028681823250598006154766'], '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testSearchBankAccounts_WhenFutureDateGiven()
    {
        $response = self::$client->searchBankAccounts(['70506405335016096312945164', '20028681823250598006154766'], (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testSearchBankAccounts_WhenAnyBankAccountHasWrongLength()
    {
        $response = self::$client->searchBankAccounts(['0'], '2021-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-109', $response->result->entries[0]->error->code);
    }

    public function testSearchBankAccounts_WhenAnyBankAccountContainsForbiddenCharacters()
    {
        $response = self::$client->searchBankAccounts(['0000000000000000000000000X'], '2021-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-110', $response->result->entries[0]->error->code);
    }

    public function testSearchBankAccounts_WhenAnyBankAccountChecksumIsInvalid()
    {
        $response = self::$client->searchBankAccounts(['00999999999999999999999999'], '2021-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-111', $response->result->entries[0]->error->code);
    }

    public function testSearchBankAccounts_WhenAnyBankAccountNotFound()
    {
        $response = self::$client->searchBankAccounts(['17249010574040965557335358'], '2021-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('17249010574040965557335358', $response->result->entries[0]->identifier);
        $this->assertSame([], $response->result->entries[0]->subjects);
    }

    public function testSearchBankAccounts_WhenAnyBankAccountFound()
    {
        $response = self::$client->searchBankAccounts(['20028681823250598006154766'], '2021-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('20028681823250598006154766', $response->result->entries[0]->identifier);
        $this->assertSame(true, is_array($response->result->entries[0]->subjects));

        $this->assertInstanceOf(Entity::class, $response->result->entries[0]->subjects[0]);
        $this->assertSame('Nazwa Firmy 7', $response->result->entries[0]->subjects[0]->name);
        $this->assertSame('3697707005', $response->result->entries[0]->subjects[0]->nip);
    }

    public function testCheckNipBankAccount_WhenEmptyDateGiven()
    {
        $response = self::$client->checkNipBankAccount('5097600783', '14852273093046683932672891', '');
        $this->assertInstanceOf(EntityCheckResponse::class, $response);

        $this->assertInstanceOf(EntityCheck::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame('TAK', $response->result->accountAssigned);
    }

    public function testCheckNipBankAccount_WhenWrongDateFormatGiven()
    {
        $response = self::$client->checkNipBankAccount('3245174504', '70506405335016096312945164', '01.01.2021');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testCheckNipBankAccount_WhenTooEarlyDateGiven()
    {
        $response = self::$client->checkNipBankAccount('3245174504', '70506405335016096312945164', '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testCheckNipBankAccount_WhenFutureDateGiven()
    {
        $response = self::$client->checkNipBankAccount('3245174504', '70506405335016096312945164', (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testCheckNipBankAccount_WhenNipHasWrongLength()
    {
        $response = self::$client->checkNipBankAccount('0', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-113', $response->code);
    }

    public function testCheckNipBankAccount_WhenNipContainsForbiddenCharacters()
    {
        $response = self::$client->checkNipBankAccount('A000000000', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-114', $response->code);
    }

    public function testCheckNipBankAccount_WhenNipChecksumIsInvalid()
    {
        $response = self::$client->checkNipBankAccount('0000000009', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-115', $response->code);
    }

    public function testCheckNipBankAccount_WhenBankAccountHasWrongLength()
    {
        $response = self::$client->checkNipBankAccount('5097600783', '0', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-109', $response->code);
    }

    public function testCheckNipBankAccount_WhenBankAccountContainsForbiddenCharacters()
    {
        $response = self::$client->checkNipBankAccount('5097600783', '0000000000000000000000000X', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-110', $response->code);
    }

    public function testCheckNipBankAccount_WhenBankAccountChecksumIsInvalid()
    {
        $response = self::$client->checkNipBankAccount('5097600783', '00999999999999999999999999', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-111', $response->code);
    }

    public function testCheckNipBankAccount_WhenNotFound()
    {
        $response = self::$client->checkNipBankAccount('5097600783', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(EntityCheckResponse::class, $response);

        $this->assertInstanceOf(EntityCheck::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame('NIE', $response->result->accountAssigned);
    }

    public function testCheckNipBankAccount_WhenFound()
    {
        $response = self::$client->checkNipBankAccount('5097600783', '14852273093046683932672891', '2020-01-01');
        $this->assertInstanceOf(EntityCheckResponse::class, $response);

        $this->assertInstanceOf(EntityCheck::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame('TAK', $response->result->accountAssigned);
    }

    public function testCheckRegonBankAccount_WhenEmptyDateGiven()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '39313859043055512144159074', '');
        $this->assertInstanceOf(EntityCheckResponse::class, $response);

        $this->assertInstanceOf(EntityCheck::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame('TAK', $response->result->accountAssigned);
    }

    public function testCheckRegonBankAccount_WhenWrongDateFormatGiven()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '70506405335016096312945164', '01.01.2021');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testCheckRegonBankAccount_WhenTooEarlyDateGiven()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '70506405335016096312945164', '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testCheckRegonBankAccount_WhenFutureDateGiven()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '70506405335016096312945164', (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testCheckRegonBankAccount_WhenRegonHasWrongLength()
    {
        $response = self::$client->checkRegonBankAccount('0', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-105', $response->code);
    }

    public function testCheckRegonBankAccount_WhenRegonContainsForbiddenCharacters()
    {
        $response = self::$client->checkRegonBankAccount('A0000000000000', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-106', $response->code);
    }

    public function testCheckRegonBankAccount_WhenRegonChecksumIsInvalid()
    {
        $response = self::$client->checkRegonBankAccount('00000000000001', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-107', $response->code);
    }

    public function testCheckRegonBankAccount_WhenBankAccountHasWrongLength()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '0', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-109', $response->code);
    }

    public function testCheckRegonBankAccount_WhenBankAccountContainsForbiddenCharacters()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '0000000000000000000000000X', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-110', $response->code);
    }

    public function testCheckRegonBankAccount_WhenBankAccountChecksumIsInvalid()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '00999999999999999999999999', '2020-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-111', $response->code);
    }

    public function testCheckRegonBankAccount_WhenNotFound()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '70506405335016096312945164', '2020-01-01');
        $this->assertInstanceOf(EntityCheckResponse::class, $response);

        $this->assertInstanceOf(EntityCheck::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame('NIE', $response->result->accountAssigned);
    }

    public function testCheckRegonBankAccount_WhenFound()
    {
        $response = self::$client->checkRegonBankAccount('79156739856513', '39313859043055512144159074', '2020-01-01');
        $this->assertInstanceOf(EntityCheckResponse::class, $response);

        $this->assertInstanceOf(EntityCheck::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame('TAK', $response->result->accountAssigned);
    }

    public function testSearchNip_WhenEmptyDateGiven()
    {
        $response = self::$client->searchNip('3245174504', '');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-101', $response->code);
    }

    public function testSearchNip_WhenWrongDateFormatGiven()
    {
        $response = self::$client->searchNip('3245174504', '01.01.2020');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testSearchNip_WhenTooEarlyDateGiven()
    {
        $response = self::$client->searchNip('3245174504', '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testSearchNip_WhenFutureDateGiven()
    {
        $response = self::$client->searchNip('3245174504', (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testSearchNip_WhenNipHasWrongLength()
    {
        $response = self::$client->searchNip('6', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-113', $response->code);
    }

    public function testSearchNip_WhenNipContainsForbiddenCharacters()
    {
        $response = self::$client->searchNip('Z000000000', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-114', $response->code);
    }

    public function testSearchNip_WhenNipChecksumIsInvalid()
    {
        $response = self::$client->searchNip('0000000009', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-115', $response->code);
    }

    public function testSearchNip_WhenNipNotFound()
    {
        $response = self::$client->searchNip('3945184775', '2021-01-01');
        $this->assertInstanceOf(EntityResponse::class, $response);

        $this->assertInstanceOf(EntityItem::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(null, $response->result->subject);
    }

    public function testSearchNip_WhenNipFound()
    {
        $response = self::$client->searchNip('3245174504', '2021-01-01');
        $this->assertInstanceOf(EntityResponse::class, $response);

        $this->assertInstanceOf(EntityItem::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entity::class, $response->result->subject);
        $this->assertSame('Nazwa Firmy 1', $response->result->subject->name);
        $this->assertSame('3245174504', $response->result->subject->nip);
    }

    public function testSearchNips_WhenEmptyNipListGiven()
    {
        $response = self::$client->searchNips([], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-112', $response->result->entries[0]->error->code);
    }

    public function testSearchNips_WhenWrongDateFormatGiven()
    {
        $response = self::$client->searchNips(['3245174504', '1854510877'], '01.01.2020');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testSearchNips_WhenEmptyDateGiven()
    {
        $response = self::$client->searchNips(['3245174504', '1854510877'], '');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-101', $response->code);
    }

    public function testSearchNips_WhenTooEarlyDateGiven()
    {
        $response = self::$client->searchNips(['3245174504', '1854510877'], '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testSearchNips_WhenFutureDateGiven()
    {
        $response = self::$client->searchNips(['3245174504', '1854510877'], (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testSearchNips_WhenAnyNipHasWrongLength()
    {
        $response = self::$client->searchNips(['0'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('0', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-113', $response->result->entries[0]->error->code);
    }

    public function testSearchNips_WhenAnyNipContainsForbiddenCharacters()
    {
        $response = self::$client->searchNips(['A000000000'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('A000000000', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-114', $response->result->entries[0]->error->code);
    }

    public function testSearchNips_WhenAnyNipChecksumIsInvalid()
    {
        $response = self::$client->searchNips(['0000000009'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('0000000009', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-115', $response->result->entries[0]->error->code);
    }

    public function testSearchNips_WhenAnyNipNotFound()
    {
        $response = self::$client->searchNips(['3945184775'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('3945184775', $response->result->entries[0]->identifier);
        $this->assertSame([], $response->result->entries[0]->subjects);
    }

    public function testSearchNips_WhenAnyNipFound()
    {
        $response = self::$client->searchNips(['3245174504'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('3245174504', $response->result->entries[0]->identifier);
        $this->assertSame(true, is_array($response->result->entries[0]->subjects));

        $this->assertInstanceOf(Entity::class, $response->result->entries[0]->subjects[0]);
        $this->assertSame('Nazwa Firmy 1', $response->result->entries[0]->subjects[0]->name);
        $this->assertSame('3245174504', $response->result->entries[0]->subjects[0]->nip);
    }

    public function testSearchRegon_WhenEmptyDateGiven()
    {
        $response = self::$client->searchRegon('79156739856513', '');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-101', $response->code);
    }

    public function testSearchRegon_WhenWrongDateFormatGiven()
    {
        $response = self::$client->searchRegon('79156739856513', '01.01.2020');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testSearchRegon_WhenTooEarlyDateGiven()
    {
        $response = self::$client->searchRegon('79156739856513', '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testSearchRegon_WhenFutureDateGiven()
    {
        $response = self::$client->searchRegon('79156739856513', (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testSearchRegon_WhenRegonHasWrongLength()
    {
        $response = self::$client->searchRegon('0', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-105', $response->code);
    }

    public function testSearchRegon_WhenRegonContainsForbiddenCharacters()
    {
        $response = self::$client->searchRegon('A0000000000000', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-106', $response->code);
    }

    public function testSearchRegon_WhenRegonChecksumIsInvalid()
    {
        $response = self::$client->searchRegon('00000000000001', '2021-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-107', $response->code);
    }

    public function testSearchRegon_WhenRegonNotFound()
    {
        $response = self::$client->searchRegon('616903225', '2021-01-01');
        $this->assertInstanceOf(EntityResponse::class, $response);

        $this->assertInstanceOf(EntityItem::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(null, $response->result->subject);
    }

    public function testSearchRegon_WhenRegonFound()
    {
        $response = self::$client->searchRegon('79156739856513', '2021-01-01');
        $this->assertInstanceOf(EntityResponse::class, $response);

        $this->assertInstanceOf(EntityItem::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));

        $this->assertInstanceOf(Entity::class, $response->result->subject);
        $this->assertSame('Nazwa Firmy 1', $response->result->subject->name);
        $this->assertSame('3245174504', $response->result->subject->nip);
    }

    public function testSearchRegons_WhenEmptyRegonListGiven()
    {
        $response = self::$client->searchRegons([], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-104', $response->result->entries[0]->error->code);
    }

    public function testSearchRegons_WhenWrongDateFormatGiven()
    {
        $response = self::$client->searchRegons(['79156739856513', '93992478603234'], '01.01.2020');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-102', $response->code);
    }

    public function testSearchRegons_WhenEmptyDateGiven()
    {
        $response = self::$client->searchRegons(['79156739856513', '93992478603234'], '');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-101', $response->code);
    }

    public function testSearchRegons_WhenTooEarlyDateGiven()
    {
        $response = self::$client->searchRegons(['79156739856513', '93992478603234'], '2000-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-118', $response->code);
    }

    public function testSearchRegons_WhenFutureDateGiven()
    {
        $response = self::$client->searchRegons(['79156739856513', '93992478603234'], (date('Y') + 1) . '-01-01');
        $this->assertInstanceOf(Error::class, $response);
        $this->assertSame('WL-103', $response->code);
    }

    public function testSearchRegons_WhenAnyRegonHasWrongLength()
    {
        $response = self::$client->searchRegons(['0'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('0', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-105', $response->result->entries[0]->error->code);
    }

    public function testSearchNips_WhenAnyRegonContainsForbiddenCharacters()
    {
        $response = self::$client->searchRegons(['A0000000000000'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('A0000000000000', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-106', $response->result->entries[0]->error->code);
    }

    public function testSearchNips_WhenAnyRegonChecksumIsInvalid()
    {
        $response = self::$client->searchRegons(['00000000000001'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('00000000000001', $response->result->entries[0]->identifier);
        $this->assertInstanceOf(Error::class, $response->result->entries[0]->error);
        $this->assertSame('WL-107', $response->result->entries[0]->error->code);
    }

    public function testSearchNips_WhenAnyRegonNotFound()
    {
        $response = self::$client->searchRegons(['616903225'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('616903225', $response->result->entries[0]->identifier);
        $this->assertSame([], $response->result->entries[0]->subjects);
    }

    public function testSearchNips_WhenAnyRegonFound()
    {
        $response = self::$client->searchRegons(['79156739856513'], '2020-01-01');
        $this->assertInstanceOf(EntryListResponse::class, $response);

        $this->assertInstanceOf(EntryList::class, $response->result);
        $this->assertSame(true, is_string($response->result->requestId));
        $this->assertSame(true, is_string($response->result->requestDateTime));
        $this->assertSame(true, is_array($response->result->entries));

        $this->assertInstanceOf(Entry::class, $response->result->entries[0]);
        $this->assertSame('79156739856513', $response->result->entries[0]->identifier);
        $this->assertSame(true, is_array($response->result->entries[0]->subjects));

        $this->assertInstanceOf(Entity::class, $response->result->entries[0]->subjects[0]);
        $this->assertSame('Nazwa Firmy 1', $response->result->entries[0]->subjects[0]->name);
        $this->assertSame('3245174504', $response->result->entries[0]->subjects[0]->nip);
    }

}
