<?php

namespace WhiteListApi\Contents;

class EntityListResponse extends Content
{
    /** @var EntityList|null */
    public ?object $result = null;


    protected function setup(): void
    {
        $this->cast('result', EntityList::class);
    }
}
