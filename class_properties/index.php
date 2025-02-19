<?php

trait Test {}

class Inherited {
    public $a = 1;
    private $b = 2;
    public $c = 'this is c';
}

class WithProperties extends Inherited {
    use Test;
    public $d = 1;
    private $e = 2;
    public $f = 'this is f';
}

class DoesNotInherit{}

print_r(get_class_vars('WithProperties'));

print get_parent_class('WithProperties');
var_dump(get_parent_class('DoesNotInherit'));

var_dump(is_subclass_of(new WithProperties(), 'Inherited'));

echo 'is_subclass_of does not check for traits' . PHP_EOL;

// false because is_subclass_of does not check for traits
var_dump(is_subclass_of(new WithProperties(), 'Test'));

var_dump(class_uses('WithProperties'));
