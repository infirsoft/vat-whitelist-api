<?php

namespace WhiteListApi\Contents;

class EntryList extends Content
{
    /** @var Entry[] */
    public $entries;

    /** @var string|null */
    public $requestId;

    /** @var string|null */
    public $requestDateTime;

    protected function setup()
    {
        $this->castArray('entries', Entry::class);
    }
}