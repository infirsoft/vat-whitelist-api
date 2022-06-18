<?php

namespace WhiteListApi\Contents;

class EntityResponse extends Content
{
    /** @var EntityItem|null */
    public ?object $result = null;


    protected function setup(): void
    {
        $this->cast('result', EntityItem::class);
    }
}
