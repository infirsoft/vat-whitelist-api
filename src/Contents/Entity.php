<?php

namespace WhiteListApi\Contents;

class Entity extends Content
{
    public string $name;

    public ?string $nip = null;

    public ?string $statusVat = null;

    public ?string $regon = null;

    public ?string $pesel = null;

    public ?string $krs = null;

    public ?string $residenceAddress = null;

    public ?string $workingAddress = null;

    /** @var EntityPerson[]|null */
    public ?array $representatives = null;

    /** @var EntityPerson[]|null */
    public ?array $authorizedClerks = null;

    /** @var EntityPerson[]|null */
    public ?array $partners = null;

    public ?string $registrationLegalDate = null;

    public ?string $registrationDenialDate = null;

    public ?string $registrationDenialBasis = null;

    public ?string $restorationDate = null;

    public ?string $restorationBasis = null;

    public ?string $removalDate = null;

    public ?string $removalBasis = null;

    /** @var string[]|null */
    public ?array $accountNumbers = null;

    public ?bool $hasVirtualAccounts = null;


    protected function setup(): void
    {
        $this->castArray('representatives', EntityPerson::class);
        $this->castArray('authorizedClerks', EntityPerson::class);
        $this->castArray('partners', EntityPerson::class);
    }
}
