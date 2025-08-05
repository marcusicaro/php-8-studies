<?php

abstract class Unit
{
    // ...
    public function accept(ArmyVisitor $visitor): void
    {
        $refthis = new \ReflectionClass(get_class($this));
        $method = "visit" . $refthis->getShortName();
        $visitor->$method($this);
    }
    protected function setDepth($depth): void
    {
        $this->depth = $depth;
    }
    public function getDepth(): int
    {
        return $this->depth;
    }
}

abstract class CompositeUnit extends Unit
{
    // ...
    public function addUnit(Unit $unit): void
    {
        foreach ($this->units as $thisunit) {
            if ($unit === $thisunit) {
                return;
            }
        }
        $unit->setDepth($this->depth + 1);
        $this->units[] = $unit;
    }
    public function accept(ArmyVisitor $visitor): void
    {
        parent::accept($visitor);
        foreach ($this->units as $thisunit) {
            $thisunit->accept($visitor);
        }
    }
}
