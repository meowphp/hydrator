<?php

namespace Meow\Hydrator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ArrayOf
{
    public function __construct(
        private string $type
    ) {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
