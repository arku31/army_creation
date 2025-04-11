<?php

namespace AG\Domain\Army\Units;

use AG\Domain\Army\Entities\Unit;
use AG\Domain\Army\Entities\UnitType;

class Spearman extends Unit
{
    public function getName(): string
    {
        return 'Spearman';
    }

    public function getType(): UnitType
    {
        return UnitType::MELEE;
    }

    public function getBaseHealth(): int
    {
        return 100;
    }

    public function getAttack(): int
    {
        return 10;
    }

    public function getDefense(): int
    {
        return 12;
    }
}