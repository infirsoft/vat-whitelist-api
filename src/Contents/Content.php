<?php

namespace WhiteListApi\Contents;

abstract class Content
{
    public function __construct(array|object $data)
    {
        foreach ($data as $field => $value) {
            $this->{$field} = $value;
        }
        $this->setup();
    }

    protected function setup(): void
    {
        // override me
    }

    protected function cast(string $field, string $class): void
    {
        if ($this->$field) {
            $this->$field = new $class($this->$field);
        }
    }

    protected function castArray(string $field, string $class): void
    {
        if (is_array($this->$field)) {
            foreach ($this->$field as $k => $v) {
                if ($this->$field[$k]) {
                    $this->$field[$k] = new $class($this->$field[$k]);
                }
            }
        }
    }
}
