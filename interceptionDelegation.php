<?php

class Person
{

    public function __construct(private PersonWriter $writer) {}

    public function __call(string $method, array $args): mixed
    {
        if (method_exists($this->writer, $method)) {
            return $this->writer->$method($this);
        }
    }

    public function getName(): string
    {
        return "Bob";
    }

    public function getAge(): int
    {
        return 44;
    }
}


// __call delegation
class PersonWriter
{
    public function writeName(Person $p): void
    {
        print $p->getName() . "\n";
    }
    public function writeAge(Person $p): void
    {
        print $p->getAge() . "\n";
    }
}

$person = new Person(new PersonWriter());
$person->writeName();