<?php

namespace WhiteListApi;

use WhiteListApi\Contents\EntityCheckResponse;
use WhiteListApi\Contents\EntityListResponse;
use WhiteListApi\Contents\EntityResponse;
use WhiteListApi\Contents\EntryListResponse;
use WhiteListApi\Contents\Error;

class WhiteListApiClient implements WhiteListApiInterface
{
    public const
        ENVIRONMENT_TEST = 'TEST';
    public const
        ENVIRONMENT_PROD = 'PROD';

    public const API_URL = [
        self::ENVIRONMENT_TEST => 'https://wl-test.mf.gov.pl',
        self::ENVIRONMENT_PROD => 'https://wl-api.mf.gov.pl',
    ];

    private string $environment;

    /**
     * WhiteList API Client constructor.
     *
     * @param string $environment
     *
     * @throws WhiteListApiException
     */
    public function __construct(string $environment = self::ENVIRONMENT_PROD)
    {
        if (!array_key_exists($environment, self::API_URL)) {
            throw new WhiteListApiException('Wrong environment');
        }
        $this->environment = $environment;
    }

    /**
     * Searching for entities by account number.
     *
     * @see https://wl-api.mf.gov.pl/#bankAccount?date
     *
     * @param string $bankAccount
     * @param string $date
     *
     * @return EntityListResponse|Error
     */
    public function searchBankAccount(string $bankAccount, string $date): EntityListResponse|Error
    {
        $pathParams = [
            '{bank-account}' => $bankAccount,
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/search/bank-account/{bank-account}', $pathParams, $queryParams),
            EntityListResponse::class
        );
    }

    /**
     * Searching for entities by account numbers.
     *
     * @see https://wl-api.mf.gov.pl/#bankAccounts?date
     *
     * @param string[] $bankAccounts
     * @param string   $date
     *
     * @return EntryListResponse|Error
     */
    public function searchBankAccounts(array $bankAccounts, string $date): EntryListResponse|Error
    {
        $pathParams = [
            '{bank-accounts}' => implode(',', $bankAccounts),
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/search/bank-accounts/{bank-accounts}', $pathParams, $queryParams),
            EntryListResponse::class
        );
    }

    /**
     * Checking a single entity by tax identification number and account number.
     *
     * @see https://wl-api.mf.gov.pl/#checkNip
     *
     * @param string $nip
     * @param string $bankAccount
     * @param string $date
     *
     * @return EntityCheckResponse|Error
     */
    public function checkNipBankAccount(string $nip, string $bankAccount, string $date): EntityCheckResponse|Error
    {
        $pathParams = [
            '{nip}'          => $nip,
            '{bank-account}' => $bankAccount,
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/check/nip/{nip}/bank-account/{bank-account}', $pathParams, $queryParams),
            EntityCheckResponse::class
        );
    }

    /**
     * Search for a single entity by tax identification number.
     *
     * @see https://wl-api.mf.gov.pl/#nip?date
     *
     * @param string $nip
     * @param string $date
     *
     * @return EntityResponse|Error
     */
    public function searchNip(string $nip, string $date): EntityResponse|Error
    {
        $pathParams = [
            '{nip}' => $nip,
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/search/nip/{nip}', $pathParams, $queryParams),
            EntityResponse::class
        );
    }

    /**
     * Checking a single entity by REGON and account number.
     *
     * @see https://wl-api.mf.gov.pl/#checkRegon
     *
     * @param string $regon
     * @param string $bankAccount
     * @param string $date
     *
     * @return EntityCheckResponse|Error
     */
    public function checkRegonBankAccount(string $regon, string $bankAccount, string $date): EntityCheckResponse|Error
    {
        $pathParams = [
            '{regon}'        => $regon,
            '{bank-account}' => $bankAccount,
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/check/regon/{regon}/bank-account/{bank-account}', $pathParams, $queryParams),
            EntityCheckResponse::class
        );
    }

    /**
     * Searching for entities by tax identification numbers.
     *
     * @see https://wl-api.mf.gov.pl/#nips?date
     *
     * @param string[] $nips
     * @param string   $date
     *
     * @return EntryListResponse|Error
     */
    public function searchNips(array $nips, string $date): EntryListResponse|Error
    {
        $pathParams = [
            '{nips}' => implode(',', $nips),
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/search/nips/{nips}', $pathParams, $queryParams),
            EntryListResponse::class
        );
    }

    /**
     * Search for a single entity by REGON.
     *
     * @see https://wl-api.mf.gov.pl/#regon?date
     *
     * @param string $regon
     * @param string $date
     *
     * @return EntityResponse|Error
     */
    public function searchRegon(string $regon, string $date): EntityResponse|Error
    {
        $pathParams = [
            '{regon}' => $regon,
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/search/regon/{regon}', $pathParams, $queryParams),
            EntityResponse::class
        );
    }

    /**
     * Searching for entities by REGON numbers.
     *
     * @see https://wl-api.mf.gov.pl/#regons?date
     *
     * @param string[] $regons
     * @param string   $date
     *
     * @return EntryListResponse|Error
     */
    public function searchRegons(array $regons, string $date): EntryListResponse|Error
    {
        $pathParams = [
            '{regons}' => implode(',', $regons),
        ];
        $queryParams = [
            'date' => $date,
        ];

        return $this->cast(
            $this->request('GET', '/api/search/regons/{regons}', $pathParams, $queryParams),
            EntryListResponse::class
        );
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $pathParams
     * @param array  $queryParams
     *
     * @return string
     */
    private function request(string $method, string $path, array $pathParams = [], array $queryParams = []): string
    {
        $url = self::API_URL[$this->environment] . strtr($path, $pathParams);
        $curl = curl_init();
        $queryParams = http_build_query($queryParams);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($method === 'GET' && !empty($queryParams)) {
            curl_setopt($curl, CURLOPT_URL, $url . '?' . $queryParams);
        }
        if ($method === 'POST' && !empty($queryParams)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $queryParams);
        }
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    private function cast(string $response, string $class)
    {
        if (!($decoded = json_decode($response))) {
            return null;
        }
        $class = (isset($decoded->code) && isset($decoded->message)) ? Error::class : $class;

        return new $class($decoded);
    }
}
