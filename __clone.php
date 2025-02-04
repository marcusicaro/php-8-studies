<?php
class CopyMe
{
    private int $id = 0;
    public function __clone()
    {
        echo "I've been cloned!";
        $this->id = 0;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}

$a = new CopyMe();
$a->setId(1);
$b = clone $a;
echo $b->getId();

class Account
{
    public function __construct(public float $balance) {}
}
class Person
{
    private int $id;
    public function __construct(
        private string $name,
        private int $age,
        public Account $account
    ) {}
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function __clone(): void
    {
        $this->id = 0;
        $this->account = clone $this->account;
    }

}
$person = new Person("bob", 44, new Account(200));
$person->setId(343);
$person2 = clone $person;

$person2->account->balance = 100;
echo $person->account->balance;