<?php

namespace WhiteListApi\Contents;

class EntityCheckResponse extends Content
{
    /** @var EntityCheck|null */
    public $result;

    public function setup()
    {
        $this->cast('result', EntityCheck::class);
    }
}