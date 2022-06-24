<?php

namespace WhiteListApi\Contents;

class EntityCheckResponse extends Content
{
    /** @var EntityCheck|null */
    public ?object $result = null;

    public function setup(): void
    {
        $this->cast('result', EntityCheck::class);
    }
}
