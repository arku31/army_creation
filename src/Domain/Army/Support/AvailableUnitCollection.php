<?php

namespace AG\Domain\Army\Support;

use AG\Domain\Army\AvailableUnitsRegistry;

class AvailableUnitCollection
{
    protected array $units = [];
    public function addUnitForAvailability(string $unit): void
    {
        if (AvailableUnitsRegistry::isAvailable($unit)) {
            $this->units[] = $unit;
        } else {
            throw new \InvalidArgumentException("Unit $unit is not available.");
        }
    }

    public function getUnits(): array
    {
        return $this->units;
    }

    public function getCount(): int
    {
        return count($this->units);
    }

    public function getByArrayKey(int $key): string
    {
        if (isset($this->units[$key])) {
            return $this->units[$key];
        }
        throw new \OutOfBoundsException("No unit found at index $key.");
    }
}