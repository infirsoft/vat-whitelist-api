<?php

namespace WhiteListApi;

use WhiteListApi\Contents\EntityCheckResponse;
use WhiteListApi\Contents\EntityListResponse;
use WhiteListApi\Contents\EntityResponse;
use WhiteListApi\Contents\EntryListResponse;

interface WhiteListApiInterface
{
    public function searchBankAccount(string $bankAccount, string $date): EntityListResponse;

    public function searchBankAccounts(array $bankAccounts, string $date): EntryListResponse;

    public function checkNipBankAccount(string $nip, string $bankAccount, string $date): EntityCheckResponse;

    public function checkRegonBankAccount(string $regon, string $bankAccount, string $date): EntityCheckResponse;

    public function searchNip(string $nip, string $date): EntityResponse;

    public function searchNips(array $nips, string $date): EntryListResponse;

    public function searchRegon(string $regon, string $date): EntityResponse;

    public function searchRegons(array $regons, string $date): EntryListResponse;
}