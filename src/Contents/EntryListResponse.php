<?php

namespace WhiteListApi\Contents;

class EntryListResponse extends Content
{
    /** @var EntryList|null */
    public ?object $result = null;


    protected function setup(): void
    {
        $this->cast('result', EntryList::class);
    }
}
