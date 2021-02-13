<?php

class WhiteListApi
{
    const API_URL = [
        'TEST' => 'https://wl-test.mf.gov.pl',
        'PROD' => 'https://wl-api.mf.gov.pl',
    ];

    private $Environment;
    private $Response;
    private $ResponseHttpCode;

    /**
     * WhiteListApi constructor.
     * @param string $environment
     * @throws Exception
     */
    public function __construct($environment = 'PROD')
    {
        if (!array_key_exists($environment, self::API_URL)) {
            throw new Exception('Wrong environment');
        }
        $this->Environment = $environment;
    }

    /**
     * @param string $bankAccount
     * @param $date
     * @return object
     */
    public function searchBankAccount($bankAccount, $date)
    {
        $pathParams = array(
            "{bank-account}" => $bankAccount,
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/search/bank-account/{bank-account}', $pathParams, $queryParams);
    }

    /**
     * @param array $bankAccounts
     * @param $date
     * @return object
     */
    public function searchBankAccounts($bankAccounts, $date)
    {
        $pathParams = array(
            "{bank-accounts}" => implode(',', $bankAccounts),
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/search/bank-accounts/{bank-accounts}', $pathParams, $queryParams);
    }

    /**
     * @param string $nip
     * @param string $bankAccount
     * @param $date
     * @return object
     */
    public function checkNipBankAccount($nip, $bankAccount, $date)
    {
        $pathParams = array(
            "{nip}" => $nip,
            "{bank-account}" => $bankAccount,
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/check/nip/{nip}/bank-account/{bank-account}', $pathParams, $queryParams);
    }

    /**
     * @param string $regon
     * @param string $bankAccount
     * @param $date
     * @return object
     */
    public function checkRegonBankAccount($regon, $bankAccount, $date)
    {
        $pathParams = array(
            "{regon}" => $regon,
            "{bank-account}" => $bankAccount,
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/check/regon/{regon}/bank-account/{bank-account}', $pathParams, $queryParams);
    }

    /**
     * @param string $nip
     * @param $date
     * @return object
     */
    public function searchNip($nip, $date)
    {
        $pathParams = array(
            "{nip}" => $nip
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/search/nip/{nip}', $pathParams, $queryParams);
    }

    /**
     * @param array $nips
     * @param $date
     * @return object
     */
    public function searchNips($nips, $date)
    {
        $pathParams = array(
            "{nips}" => implode(',', $nips)
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/search/nips/{nips}', $pathParams, $queryParams);
    }

    /**
     * @param string $regon
     * @param $date
     * @return object
     */
    public function searchRegon($regon, $date)
    {
        $pathParams = array(
            "{regon}" => $regon
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/search/regon/{regon}', $pathParams, $queryParams);
    }

    /**
     * @param array $regons
     * @param $date
     * @return object
     */
    public function searchRegons($regons, $date)
    {
        $pathParams = array(
            "{regons}" => implode(',', $regons)
        );
        $queryParams = array(
            'date' => $date
        );
        return $this->request('GET', '/api/search/regons/{regons}', $pathParams, $queryParams);
    }

    /**
     * @param $method
     * @param $path
     * @param array $pathParams
     * @param array $queryParams
     * @return object
     */
    private function request($method, $path, $pathParams = [], $queryParams = [])
    {
        $url = self::API_URL[$this->Environment] . strtr($path, $pathParams);
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
        $this->Response = json_decode(curl_exec($curl), true);
        $this->ResponseHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $this->Response;
    }

    /**
     * @return array|null
     */
    public function getResponse()
    {
        return $this->Response;
    }

    /**
     * @return int|null
     */
    public function getResponseHttpCode()
    {
        return $this->ResponseHttpCode;
    }

}