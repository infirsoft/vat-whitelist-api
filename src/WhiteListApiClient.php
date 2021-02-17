<?php

namespace WhiteListApi;

use WhiteListApi\Contents\EntityCheckResponse;
use WhiteListApi\Contents\EntityListResponse;
use WhiteListApi\Contents\EntityResponse;

class WhiteListApiClient implements WhiteListApiInterface
{
  const API_URL = [
    'TEST' => 'https://wl-test.mf.gov.pl',
    'PROD' => 'https://wl-api.mf.gov.pl',
  ];

  private $Environment;

  /**
   * Client constructor.
   * @param string $environment
   * @throws WhiteListApiException
   */
  public function __construct(string $environment = 'PROD')
  {
    if (!array_key_exists($environment, self::API_URL)) {
      throw new WhiteListApiException('Wrong environment');
    }
    $this->Environment = $environment;
  }

  /**
   * @param string $bankAccount
   * @param string $date
   * @return EntityListResponse
   * @throws WhiteListApiException
   */
  public function searchBankAccount(string $bankAccount, string $date): EntityListResponse
  {
    $pathParams = array(
      "{bank-account}" => $bankAccount,
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/search/bank-account/{bank-account}', $pathParams, $queryParams),
      EntityListResponse::class
    );
  }

  /**
   * @param array[string] $bankAccounts
   * @param string $date
   * @return EntityListResponse
   * @throws WhiteListApiException
   */
  public function searchBankAccounts(array $bankAccounts, string $date): EntityListResponse
  {
    $pathParams = array(
      "{bank-accounts}" => implode(',', $bankAccounts),
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/search/bank-accounts/{bank-accounts}', $pathParams, $queryParams),
      EntityListResponse::class
    );
  }

  /**
   * @param string $nip
   * @param string $bankAccount
   * @param string $date
   * @return EntityCheckResponse
   * @throws WhiteListApiException
   */
  public function checkNipBankAccount(string $nip, string $bankAccount, string $date): EntityCheckResponse
  {
    $pathParams = array(
      "{nip}" => $nip,
      "{bank-account}" => $bankAccount,
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/check/nip/{nip}/bank-account/{bank-account}', $pathParams, $queryParams),
      EntityCheckResponse::class
    );
  }

  /**
   * @param string $regon
   * @param string $bankAccount
   * @param string $date
   * @return EntityCheckResponse
   * @throws WhiteListApiException
   */
  public function checkRegonBankAccount(string $regon, string $bankAccount, string $date): EntityCheckResponse
  {
    $pathParams = array(
      "{regon}" => $regon,
      "{bank-account}" => $bankAccount,
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/check/regon/{regon}/bank-account/{bank-account}', $pathParams, $queryParams),
      EntityCheckResponse::class
    );
  }

  /**
   * @param string $nip
   * @param string $date
   * @return EntityResponse
   * @throws WhiteListApiException
   */
  public function searchNip(string $nip, string $date): EntityResponse
  {
    $pathParams = array(
      "{nip}" => $nip
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/search/nip/{nip}', $pathParams, $queryParams),
      EntityResponse::class
    );
  }

  /**
   * @param array[string] $nips
   * @param string $date
   * @return EntityListResponse
   * @throws WhiteListApiException
   */
  public function searchNips(array $nips, string $date): EntityListResponse
  {
    $pathParams = array(
      "{nips}" => implode(',', $nips)
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/search/nips/{nips}', $pathParams, $queryParams),
      EntityListResponse::class
    );
  }

  /**
   * @param string $regon
   * @param string $date
   * @return EntityResponse
   * @throws WhiteListApiException
   */
  public function searchRegon(string $regon, string $date): EntityResponse
  {
    $pathParams = array(
      "{regon}" => $regon
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/search/regon/{regon}', $pathParams, $queryParams),
      EntityResponse::class
    );
  }

  /**
   * @param array[string] $regons
   * @param string $date
   * @return EntityListResponse
   * @throws WhiteListApiException
   */
  public function searchRegons(array $regons, string $date): EntityListResponse
  {
    $pathParams = array(
      "{regons}" => implode(',', $regons)
    );
    $queryParams = array(
      'date' => $date
    );
    return $this->cast(
      $this->request('GET', '/api/search/regons/{regons}', $pathParams, $queryParams),
      EntityListResponse::class
    );
  }

  /**
   * @param string $method
   * @param string $path
   * @param array $pathParams
   * @param array $queryParams
   * @return bool|mixed|string
   * @throws WhiteListApiException
   */
  private function request(string $method, string $path, array $pathParams, array $queryParams)
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
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    switch ($httpCode) {
      case 200:
        return $response;
      default:
        throw new WhiteListApiException;
    }
  }

  /**
   * @param string $json
   * @param string $class
   * @return mixed
   */
  private function cast(string $json, string $class)
  {
    $obj = new $class;
    foreach (json_decode($json) as $k => $v) {
      $obj->{$k} = $v;
    }
    return $obj;
  }

}