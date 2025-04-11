<?php

namespace AG\Presentation\CLI;

use AG\Application\Army\ArmyGeneratorService;
use AG\Domain\Army\Entities\Troop;
use AG\Infrastructure\Random\Randomizer;
use AG\Infrastructure\Random\RandomTroopDistributer;

class GenerateArmy
{
    public function run(array $argv)
    {
        if (empty($argv[1])) {
            echo "Please provide the number of units to generate.\n";
            return;
        }

        if (!is_numeric($argv[1])) {
            echo "The number of units must be a numeric value.\n";
            return;
        }

        $service = new ArmyGeneratorService(new RandomTroopDistributer(new Randomizer()));
        $army = $service->createArmy($argv[1]);

        echo "Generated Army size {$argv[1]}:\n";

        /** @var Troop $troop */
        foreach ($army as $troop) {
            echo $troop->getCount() . " " . $troop->getUnitName() . "\n";
        }
    }
}