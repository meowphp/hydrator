<?php

namespace Meow\Hydrator\Test\Model;

use Meow\Hydrator\Attributes\ArrayOf;

class Character
{
    protected string $name;

    protected string $characterClass;

    protected Equipment $equipment;

    #[ArrayOf(Weapon::class)]
    protected array $inventory;

    public function __construct(string $name, string $characterClass, Equipment $equipment, array $inventory = [])
    {
        $this->name = $name;
        $this->characterClass = $characterClass;
        $this->equipment = $equipment;
        $this->inventory = $inventory;
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

    public function getInventory(): array
    {
        return $this->inventory;
    }
}