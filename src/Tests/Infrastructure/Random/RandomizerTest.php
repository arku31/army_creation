<?php

namespace AG\Tests\Infrastructure\Random;

use AG\Domain\Army\Support\AvailableUnitCollection;
use AG\Domain\Army\Units\Archer;
use AG\Domain\Army\Units\Horseman;
use AG\Domain\Army\Units\Spearman;
use AG\Domain\Army\Units\Swordsman;
use AG\Infrastructure\Random\Randomizer;
use PHPUnit\Framework\TestCase;

class RandomizerTest extends TestCase
{
    public function testRandomApproxChunksReturnsCorrectSum()
    {
        $randomizer = new Randomizer();
        $unitCollection = new AvailableUnitCollection();
        $unitCollection->addUnitForAvailability(Archer::class);
        $unitCollection->addUnitForAvailability(Horseman::class);
        $unitCollection->addUnitForAvailability(Swordsman::class);
        $unitCollection->addUnitForAvailability(Spearman::class);

        $total = 157;
        $result = $randomizer->randomApproxChunks($total, $unitCollection);

        $this->assertEquals($total, array_sum($result));
    }

    public function testRandomApproxChunksReturnsCorrectSumSmall()
    {
        $randomizer = new Randomizer();
        $unitCollection = new AvailableUnitCollection();
        $unitCollection->addUnitForAvailability(Archer::class);
        $unitCollection->addUnitForAvailability(Horseman::class);
        $unitCollection->addUnitForAvailability(Swordsman::class);
        $unitCollection->addUnitForAvailability(Spearman::class);

        $total = 5;
        $result = $randomizer->randomApproxChunks($total, $unitCollection);

        $this->assertEquals($total, array_sum($result));
        foreach ($result as $item) {
            $this->assertGreaterThanOrEqual(1, $item);
        }
    }
}