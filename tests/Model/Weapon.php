<?php

namespace Meow\Hydrator\Test\Model;

class Weapon
{
    protected string $name;

    protected array $damage;

    public function __construct(string $name, array $damage)
    {
        $this->name = $name;
        $this->damage = $damage;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDamage(): array
    {
        return $this->damage;
    }
}