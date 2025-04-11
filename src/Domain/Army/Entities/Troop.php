<?php

namespace AG\Domain\Army\Entities;

class Troop
{
    private array $units;

    public function __construct(Unit $unit)
    {
        $this->units[] = $unit;
    }

    public function addUnit(Unit $unit): void
    {
        if (get_class($unit) !== $this->getUnitClass()) {
            throw new \InvalidArgumentException("All units in a troop must be of the same type.");
        }
        $this->units[] = $unit;
    }

    public function getUnits(): array
    {
        return $this->units;
    }

    public function getCount(): int
    {
        return count($this->units);
    }

    public function getUnitClass(): string
    {
        return get_class($this->units[0]);
    }

    public function getUnitName(): string
    {
        $pieces = explode('\\', $this->getUnitClass());
        return end($pieces);
    }
}