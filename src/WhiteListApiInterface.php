<?php

namespace WhiteListApi;

interface WhiteListApiInterface
{
    public function searchBankAccount(string $bankAccount, string $date);

    public function searchBankAccounts(array $bankAccounts, string $date);

    public function checkNipBankAccount(string $nip, string $bankAccount, string $date);

    public function checkRegonBankAccount(string $regon, string $bankAccount, string $date);

    public function searchNip(string $nip, string $date);

    public function searchNips(array $nips, string $date);

    public function searchRegon(string $regon, string $date);

    public function searchRegons(array $regons, string $date);
}