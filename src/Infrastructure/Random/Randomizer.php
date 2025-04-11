<?php

namespace AG\Infrastructure\Random;

use AG\Domain\Army\Support\AvailableUnitCollection;

class Randomizer
{
    public function randomApproxChunks(int $number, AvailableUnitCollection $availableUnitCollection): array {

        $result = [];
        $retainedUnits = $number;
        foreach ($availableUnitCollection->getUnits() as $unit) {
            $result[$unit] = 1;
            $retainedUnits--;
        }

        //we fill 50% of availability fairly

        $fairAllocationNumber = intval(floor($retainedUnits / $availableUnitCollection->getCount()) * 0.5);

        foreach ($availableUnitCollection->getUnits() as $unit) {
            if ($retainedUnits > 0) {
                $result[$unit] += $fairAllocationNumber;
                $retainedUnits = $retainedUnits - $fairAllocationNumber;
            }
        }

        //we fill the rest randomly
        for ($i=0;$i<$retainedUnits;$i++)    {
            $randomIndex = rand(0, $availableUnitCollection->getCount() - 1);
            $result[$availableUnitCollection->getByArrayKey($randomIndex)]++;
        }

        return $result;
    }
}