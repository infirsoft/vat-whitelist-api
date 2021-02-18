<?php

namespace WhiteListApi\Contents;

class Entry
{
    /** @var string */
    public $identifier;

    /** @var Entity[]|null */
    public $subjects;

    /** @var Error|null */
    public $error;
}