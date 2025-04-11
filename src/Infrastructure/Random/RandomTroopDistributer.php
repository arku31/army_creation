<?php

namespace AG\Infrastructure\Random;

use AG\Domain\Army\Entities\Troop;
use AG\Domain\Army\Entities\Unit;
use AG\Domain\Army\Support\AvailableUnitCollection;
use AG\Domain\Army\Units\Archer;
use AG\Domain\Army\Units\Horseman;
use AG\Domain\Army\Units\Spearman;
use AG\Domain\Army\Units\Swordsman;

class RandomTroopDistributer
{

    public function __construct(private Randomizer $randomizer)
    {

    }

    /**
     * @param int $numberOfUnits
     * @param AvailableUnitCollection $supportedUnits
     * @return Troop[]
     */
    public function distributeTroops(int $numberOfUnits, AvailableUnitCollection $supportedUnits): array
    {
        $units = [];
        if ($numberOfUnits <= $supportedUnits->getCount()) {
            //if army is too small - we fill it up as we can :shrug:
            for ($i=0;$i<$numberOfUnits;$i++) {
                $units[]= $this->createTroops($supportedUnits->getByArrayKey($i), 1);
            }
            return $units;
        }

        $chunks = $this->randomizer->randomApproxChunks($numberOfUnits, $supportedUnits);
        foreach ($chunks as $unitClass => $numberOfUnits) {
            if ($numberOfUnits > 0) {
                $units[] = $this->createTroops($unitClass, $numberOfUnits);
            }
        }

        return $units;
    }

    private function createUnit(string $className): Unit
    {
        return match ($className) {
            Archer::class => new Archer(),
            Horseman::class => new Horseman(),
            Swordsman::class => new Swordsman(),
            Spearman::class => new Spearman(),
            default => throw new \InvalidArgumentException("Invalid unit class: $className"),
        };
    }

    private function createTroops(string $className, int $numberOfUnits): Troop
    {
        $troop = new Troop($this->createUnit($className));
        for ($i = 1; $i < $numberOfUnits; $i++) {
            $troop->addUnit($this->createUnit($className));
        }
        return $troop;
    }
}