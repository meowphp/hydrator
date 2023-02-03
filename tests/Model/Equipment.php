<?php

namespace Meow\Hydrator\Test\Model;

class Equipment
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}