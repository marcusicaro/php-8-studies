<?php
class Person
{
    private ?string $myname = null;
    private ?int $myage = null;

    public function __get(string $property): mixed
    {
        $method = "get{$property}";
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        return null;
    }

    public function __isset(string $property): bool
    {
        $method = "get{$property}";
        return method_exists($this, $method);
    }

    public function __set(string $property, mixed $value): void
    {
        $method = "set{$property}";
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    public function setName(?string $name): void
    {
        $this->myname = $name;
        if (! is_null($name)) {
            $this->myname = strtoupper($this->myname);
        }
    }

    public function setAge(?int $age): void
    {
        $this->myage = $age;
    }

    public function getName(): ?string
    {
        return $this->myname;
    }

    public function getAge(): ?int
    {
        return $this->myage;
    }

    public function __unset(string $property): void
    {
        $method = "set{$property}";
        if (method_exists($this, $method)) {
            $this->$method(null);
        }
    }
}

$p = new Person();
$p->name = "bob";
print $p->getName();
$p->__unset('name');
print $p->getName();