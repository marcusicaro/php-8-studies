<?php
abstract class DomainObject
{
    private string $group;
    public function __construct()
    {
        $this->group = static::getGroup();
    }
    public static function create(): DomainObject
    {
        return new static();
    }
    public static function getGroup(): string
    {
        return "default";
    }
}
// listing 04.58
class User extends DomainObject {}
// listing 04.59
class Document extends DomainObject
{
    public static function getGroup(): string
    {
        return "document";
    }
}
// listing 04.60
class SpreadSheet extends Document {}
// listing 04.61
print_r(User::create());
print_r(SpreadSheet::create());
