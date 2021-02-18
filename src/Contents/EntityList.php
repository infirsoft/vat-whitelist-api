<?php

namespace WhiteListApi\Contents;

class EntityList extends Content
{
    /** @var Entity[]|null */
    public $subjects;

    /** @var string|null */
    public $requestId;

    /** @var string|null */
    public $requestDateTime;

    protected function setup()
    {
        $this->castArray('subjects', Entity::class);
    }
}