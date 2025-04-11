<?php

namespace AG\Presentation\API;

use AG\Application\Army\ArmyGeneratorService;
use AG\Domain\Army\Entities\Troop;
use AG\Domain\Army\Entities\Unit;
use AG\Infrastructure\Random\Randomizer;
use AG\Infrastructure\Random\RandomTroopDistributer;

class GenerateArmyAPI
{
    public function run(array $request)
    {
        $armySize = $request['armySize'] ?? null;
        if (empty($armySize)) {
            return [
                'error' => 'Please provide the `armySize` to generate.'
            ];
        }
        $service = new ArmyGeneratorService(new RandomTroopDistributer(new Randomizer()));
        $army = $service->createArmy($armySize);

        return [//this should normally go to Transformer but for simplicity we will do it here
            'armySize' => intval($armySize),
            'troops' => array_map(function (Troop $troop) {
                return [
                    'count' => $troop->getCount(),
                    'unitType' =>  $troop->getUnitName(),
                    'units' => array_map(function(Unit $unit) {
                        return $unit->toArray();
                    }, $troop->getUnits())
                ];
            }, $army)
        ];
    }
}