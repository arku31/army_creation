<?php
namespace AG\Domain\Army\Entities;

abstract class Unit
{

    abstract public function getName(): string;
    abstract public function getType(): UnitType;
    abstract public function getBaseHealth(): int;
    abstract public function getAttack(): int;
    abstract public function getDefense(): int;

    //Each person is unique, right?
    public function getModifiedAttack(): int
    {
        $variation = round($this->getAttack() * 0.1);
        return intval(($this->getAttack() + random_int(-$variation, $variation)) * $this->getType()->getAttackModifier());
    }

    public function getModifiedDefense(): int
    {
        $variation = round($this->getDefense() * 0.1);
        return intval(($this->getDefense() + random_int(-$variation, $variation)) * $this->getType()->getDefenseModifier());
    }

    public function getModifiedHealth(): int
    {
        $variation = round($this->getBaseHealth() * 0.1);
        return intval(($this->getBaseHealth() + random_int(-$variation, $variation)) * $this->getType()->getHealthModifier());
    }

    public function toArray(): array
    {
        return [
            'troopType' => $this->getType()->name,
            'health' => $this->getModifiedHealth(),
            'attack' => $this->getModifiedAttack(),
            'defense' => $this->getModifiedDefense(),
        ];
    }


}