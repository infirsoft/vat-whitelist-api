<?php

namespace WhiteListApi\Contents;

class EntityListResponse extends Content
{
    /** @var EntityList|null */
    public $result;

    protected function setup()
    {
        $this->cast('result', EntityList::class);
    }
}