<?php

namespace AG\Domain\Army;

use AG\Domain\Army\Units\Archer;
use AG\Domain\Army\Units\Horseman;
use AG\Domain\Army\Units\Spearman;
use AG\Domain\Army\Units\Swordsman;

class AvailableUnitsRegistry
{
    public static function getAvailableUnits(): array
    {
        return [ //this is a list of all available units. If you want to add a new unit, add it here.
            Archer::class,
            Horseman::class,
            Swordsman::class,
            Spearman::class,
        ];
    }

    public static function isAvailable(string $unit): bool
    {
        $availableUnits = self::getAvailableUnits();

        return in_array($unit, $availableUnits, true);
    }
}
