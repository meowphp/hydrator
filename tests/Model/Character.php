<?php

namespace Meow\Hydrator\Test\Model;

class Character
{
    protected string $name;

    protected string $characterClass;

    protected Equipment $equipment;

    public function __construct(string $name, string $characterClass, Equipment $equipment)
    {
        $this->name = $name;
        $this->characterClass = $characterClass;
        $this->equipment = $equipment;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCharacterClass(): string
    {
        return $this->characterClass;
    }

    public function getEquipment(): Equipment
    {
        return $this->equipment;
    }
}