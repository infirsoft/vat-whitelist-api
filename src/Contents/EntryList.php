<?php

namespace WhiteListApi\Contents;

class EntryList extends Content
{
    /** @var Entry[] */
    public array $entries;

    public ?string $requestId = null;

    public ?string $requestDateTime = null;


    protected function setup(): void
    {
        $this->castArray('entries', Entry::class);
    }
}
