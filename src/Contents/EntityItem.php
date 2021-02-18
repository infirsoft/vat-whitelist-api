<?php

namespace WhiteListApi\Contents;

class EntityItem extends Content
{
    /** @var Entity|null */
    public $subject;

    /** @var string|null */
    public $requestId;

    /** @var string|null */
    public $requestDateTime;

    protected function setup()
    {
        $this->cast('subject', Entity::class);
    }
}