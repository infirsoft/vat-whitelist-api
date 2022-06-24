<?php

namespace WhiteListApi\Contents;

class EntityList extends Content
{
    /** @var Entity[]|null */
    public ?array $subjects = null;

    public ?string $requestId = null;

    public ?string $requestDateTime = null;

    protected function setup(): void
    {
        $this->castArray('subjects', Entity::class);
    }
}
