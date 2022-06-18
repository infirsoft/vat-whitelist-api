<?php

namespace WhiteListApi\Contents;

class EntityItem extends Content
{
    /** @var Entity|null */
    public ?object $subject = null;

    public ?string $requestId = null;

    public ?string $requestDateTime = null;


    protected function setup(): void
    {
        $this->cast('subject', Entity::class);
    }
}
