<?php

namespace WhiteListApi\Contents;

class Entry extends Content
{
    /** @var string */
    public $identifier;

    /** @var Entity[]|null */
    public $subjects;

    /** @var Error|null */
    public $error;

    protected function setup()
    {
        $this->castArray('subjects', Entity::class);
        $this->cast('error', Error::class);
    }
}