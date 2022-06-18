<?php

namespace WhiteListApi\Contents;

class Entry extends Content
{
    /** @var string */
    public string $identifier;

    /** @var Entity[]|null */
    public ?array $subjects = null;

    /** @var Error|null */
    public ?object $error = null;


    protected function setup(): void
    {
        $this->castArray('subjects', Entity::class);
        $this->cast('error', Error::class);
    }
}
