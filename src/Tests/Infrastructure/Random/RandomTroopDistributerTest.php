<?php

namespace AG\Tests\Infrastructure\Random;

use AG\Domain\Army\Support\AvailableUnitCollection;
use AG\Domain\Army\Units\Archer;
use AG\Domain\Army\Units\Horseman;
use AG\Domain\Army\Units\Spearman;
use AG\Domain\Army\Units\Swordsman;
use AG\Infrastructure\Random\Randomizer;
use AG\Infrastructure\Random\RandomTroopDistributer;
use PHPUnit\Framework\TestCase;

class RandomTroopDistributerTest extends TestCase
{
    public function testBigDistribution()
    {
        $troopDistribution = new RandomTroopDistributer(new Randomizer());
        $unitCollection = new AvailableUnitCollection();
        $unitCollection->addUnitForAvailability(Archer::class);
        $unitCollection->addUnitForAvailability(Horseman::class);
        $unitCollection->addUnitForAvailability(Swordsman::class);

        $troops = $troopDistribution->distributeTroops(20, $unitCollection);
        $sum = 0;
        foreach ($troops as $troop) {
            $sum+= $troop->getCount();
        }
        $this->assertEquals(20, $sum);
    }

    public function testSmallDistribution()
    {
        $troopDistribution = new RandomTroopDistributer(new Randomizer());
        $unitCollection = new AvailableUnitCollection();
        $unitCollection->addUnitForAvailability(Archer::class);
        $unitCollection->addUnitForAvailability(Horseman::class);
        $unitCollection->addUnitForAvailability(Swordsman::class);
        $unitCollection->addUnitForAvailability(Spearman::class);

        $troops = $troopDistribution->distributeTroops(2, $unitCollection);
        $sum = 0;
        foreach ($troops as $troop) {
            $sum+= $troop->getCount();
        }
        $this->assertEquals(2, $sum);
    }
}