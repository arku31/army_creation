<?php

namespace AG\Domain\Army\Units;

use AG\Domain\Army\Entities\Unit;
use AG\Domain\Army\Entities\UnitType;

class Horseman extends Unit
{
    public function getName(): string
    {
        return 'Horseman';
    }

    public function getType(): UnitType
    {
        return UnitType::CAVALRY;
    }

    public function getBaseHealth(): int
    {
        return 200;
    }

    public function getAttack(): int
    {
        return 20;
    }

    public function getDefense(): int
    {
        return 10;
    }
}