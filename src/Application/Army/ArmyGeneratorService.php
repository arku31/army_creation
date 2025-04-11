<?php
namespace AG\Application\Army;

use AG\Domain\Army\Support\AvailableUnitCollection;
use AG\Domain\Army\Units\Archer;
use AG\Domain\Army\Units\Horseman;
use AG\Domain\Army\Units\Spearman;
use AG\Domain\Army\Units\Swordsman;
use AG\Infrastructure\Random\RandomTroopDistributer;

class ArmyGeneratorService
{
    public function __construct(private RandomTroopDistributer $troopDistributer)
    {

    }
    public function createArmy(int $numberOfUnits): array
    {
        $unitCollection = new AvailableUnitCollection();
        $unitCollection->addUnitForAvailability(Archer::class);
        $unitCollection->addUnitForAvailability(Horseman::class);
        $unitCollection->addUnitForAvailability(Swordsman::class);
        $unitCollection->addUnitForAvailability(Spearman::class);

        return $this->troopDistributer->distributeTroops($numberOfUnits, $unitCollection);
    }
}