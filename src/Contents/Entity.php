<?php

namespace WhiteListApi\Contents;

class Entity
{
  /** @var string */
  public $name;

  /** @var string|null */
  public $nip;

  /** @var string|null */
  public $statusVat;

  /** @var string|null */
  public $regon;

  /** @var string|null */
  public $pesel;

  /** @var string|null */
  public $krs;

  /** @var string|null */
  public $residenceAddress;

  /** @var string|null */
  public $workingAddress;

  /** @var array[EntityPerson]|null */
  public $representatives;

  /** @var array[EntityPerson]|null */
  public $authorizedClerks;

  /** @var array[EntityPerson]|null */
  public $partners;

  /** @var string|null */
  public $registrationLegalDate;

  /** @var string|null */
  public $registrationDenialDate;

  /** @var string|null */
  public $registrationDenialBasis;

  /** @var string|null */
  public $restorationDate;

  /** @var string|null */
  public $restorationBasis;

  /** @var string|null */
  public $removalDate;

  /** @var string|null */
  public $removalBasis;

  /** @var array[string]|null */
  public $accountNumbers;

  /** @var boolean|null */
  public $hasVirtualAccounts;
}