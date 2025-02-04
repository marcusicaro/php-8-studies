<?php
class StringThing
{
    public static function printThing(string|\Stringable $str): void
    {
        print $str;
    }
}


class Person
{
    public function getName(): string
    {
        return "Bob";
    }
    public function getAge(): int
    {
        return 44;
    }
    public function __toString(): string
    {
        $desc = $this->getName() . " (age ";
        $desc .= $this->getAge() . ")";
        return $desc;
    }
}


$a = new Person();
echo $a;
$st = new StringThing();
$st->printThing($a);