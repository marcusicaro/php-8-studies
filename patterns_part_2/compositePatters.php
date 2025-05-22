<?php

abstract class Unit
{
    abstract public function bombardStrength(): int;
}
class Archer extends Unit
{
    public function bombardStrength(): int
    {
        return 4;
    }
}
class LaserCannonUnit extends Unit
{
    public function bombardStrength(): int
    {
        return 44;
    }
}

class Army
{
    private array $units = [];
    private array $armies = [];

    public function addArmy(Army $army): void
    {
        array_push($this->armies, $army);
    }

    public function addUnit(Unit $unit): void
    {
        array_push($this->units, $unit);
    }
    public function bombardStrength(): int
    {
        $ret = 0;
        foreach ($this->units as $unit) {
            $ret += $unit->bombardStrength();
        }
        foreach ($this->armies as $army) {
            $ret += $army->bombardStrength();
        }

        return $ret;
    }
}

$unit1 = new Archer();
$unit2 = new LaserCannonUnit();
$army = new Army();
$army->addUnit($unit1);
$army->addUnit($unit2);
print $army->bombardStrength();
