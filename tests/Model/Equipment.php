<?php

namespace Meow\Hydrator\Test\Model;

class Equipment
{
    protected string $name;

    protected Weapon $weapon;

    public function __construct(string $name, Weapon $weapon)
    {
        $this->name = $name;
        $this->weapon = $weapon;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeapon(): Weapon
    {
        return $this->weapon;
    }
}