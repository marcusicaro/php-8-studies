<?php

namespace generating_objects;

abstract class Employee
{
    private static $types = ['Minion', 'CluedUp', 'WellConnected'];

    public function __construct(protected string $name) {}

    public static function recruit(string $name): Employee
    {
        $num = rand(1, count(self::$types)) - 1;
        $class = __NAMESPACE__ . "\\" . self::$types[$num];
        return new $class($name);
    }

    abstract public function fire(): void;
}

class Minion extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll clear my desk\n";
    }
}

class CluedUp extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll call my lawyer\n";
    }
}

class NastyBoss
{
    private array $employees = [];
    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
    }
    public function projectFails(): void
    {
        if (count($this->employees) > 0) {
            $emp = array_pop($this->employees);
            $emp->fire();
        }
    }
}

class WellConnected extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll call my dad\n";
    }
}


$boss = new NastyBoss();
$boss->addEmployee(Employee::recruit("harry"));
$boss->addEmployee(Employee::recruit("bob"));
$boss->addEmployee(Employee::recruit("mary"));
$boss->projectFails();
$boss->projectFails();
$boss->projectFails();
