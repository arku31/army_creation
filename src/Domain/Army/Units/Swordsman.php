<?php

namespace AG\Domain\Army\Units;

use AG\Domain\Army\Entities\Unit;
use AG\Domain\Army\Entities\UnitType;

class Swordsman extends Unit
{
    public function getName(): string
    {
        return 'Swordsman';
    }

    public function getType(): UnitType
    {
        return UnitType::MELEE;
    }

    public function getBaseHealth(): int
    {
        return 150;
    }

    public function getAttack(): int
    {
        return 12;
    }

    public function getDefense(): int
    {
        return 15;
    }
}