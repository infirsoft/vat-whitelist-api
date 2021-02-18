<?php

namespace WhiteListApi\Contents;

class EntryListResponse extends Content
{
    /** @var EntryList|null */
    public $result;

    protected function setup()
    {
        $this->cast('result', EntryList::class);
    }
}