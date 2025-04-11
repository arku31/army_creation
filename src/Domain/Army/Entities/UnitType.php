<?php

namespace AG\Domain\Army\Entities;

enum UnitType
{
    case MELEE;
    case RANGED;
    case CAVALRY;

    public function getHealthModifier(): float
    {
        return match ($this) {
            self::MELEE => 1.0,
            self::RANGED => 0.8,
            self::CAVALRY => 1.2,
        };
    }

    public function getAttackModifier(): float
    {
        return match ($this) {
            self::MELEE => 1.2,
            self::RANGED => 1.0,
            self::CAVALRY => 1.5,
        };
    }

    public function getDefenseModifier(): float
    {
        return match ($this) {
            self::MELEE => 1.0,
            self::RANGED => 0.8,
            self::CAVALRY => 1.2,
        };
    }
}