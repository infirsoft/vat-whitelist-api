<?php

namespace WhiteListApi\Contents;

class EntityResponse extends Content
{
    /** @var EntityItem|null */
    public $result;

    protected function setup()
    {
        $this->cast('result', EntityItem::class);
    }
}