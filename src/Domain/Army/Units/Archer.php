<?php

namespace AG\Domain\Army\Units;

use AG\Domain\Army\Entities\Unit;
use AG\Domain\Army\Entities\UnitType;

class Archer extends Unit
{
    public function getName(): string
    {
        return 'Archer';
    }

    public function getType(): UnitType
    {
        return UnitType::RANGED;
    }

    public function getBaseHealth(): int
    {
        return 50;
    }

    public function getAttack(): int
    {
        return 50;
    }

    public function getDefense(): int
    {
        return 5;
    }
}