<?php

// abstract class Unit
// {
//     abstract public function bombardStrength(): int;
// }
// class Archer extends Unit
// {
//     public function bombardStrength(): int
//     {
//         return 4;
//     }
// }
// class LaserCannonUnit extends Unit
// {
//     public function bombardStrength(): int
//     {
//         return 44;
//     }
// }

// class Army
// {
//     private array $units = [];
//     private array $armies = [];

//     public function addArmy(Army $army): void
//     {
//         array_push($this->armies, $army);
//     }

//     public function addUnit(Unit $unit): void
//     {
//         array_push($this->units, $unit);
//     }
//     public function bombardStrength(): int
//     {
//         $ret = 0;
//         foreach ($this->units as $unit) {
//             $ret += $unit->bombardStrength();
//         }
//         foreach ($this->armies as $army) {
//             $ret += $army->bombardStrength();
//         }

//         return $ret;
//     }
// }

// $unit1 = new Archer();
// $unit2 = new LaserCannonUnit();
// $army = new Army();
// $army->addUnit($unit1);
// $army->addUnit($unit2);
// print $army->bombardStrength();

abstract class Unit
{
    public function addUnit(Unit $unit): void
    {
        throw new UnitException(get_class($this) . " is a leaf");
    }
    public function removeUnit(Unit $unit): void
    {
        throw new UnitException(get_class($this) . " is a leaf");
    }
    abstract public function bombardStrength(): int;
}

class Army extends Unit
{
    private array $units = [];
    public function addUnit(Unit $unit): void
    {
        if (in_array($unit, $this->units, true)) {
            return;
        }
        $this->units[] = $unit;
    }
    public function removeUnit(Unit $unit): void
    {
        $idx = array_search($unit, $this->units, true);
        if (is_int($idx)) {
            array_splice($this->units, $idx, 1, []);
        }
    }
    public function bombardStrength(): int
    {
        $ret = 0;
        foreach ($this->units as $unit) {
            $ret += $unit->bombardStrength();
        }
        return $ret;
    }
}

class UnitException extends \Exception {}

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
// listing 10.12
// create an army
$main_army = new Army();
// add some units
$main_army->addUnit(new Archer());
$main_army->addUnit(new LaserCannonUnit());
// create a new army
$sub_army = new Army();
// add some units
$sub_army->addUnit(new Archer());
$sub_army->addUnit(new Archer());
$sub_army->addUnit(new Archer());
// add the second army to the first
$main_army->addUnit($sub_army);
// all the calculations handled behind the scenes
print "attacking with strength: {$main_army->bombardStrength()}\n";