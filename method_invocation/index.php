<?php

class NewClass {
    public function test()
    {
        echo 'a';
    }

    public function test2(string $content)
    {
        echo $content;
    }
}

class NewClassContainer {
    public $new_class;

    public function __construct()
    {
        $this->new_class = new NewClass();
    }

    public function __call(string $method, array $args)
    {
        if (method_exists($this->new_class, $method)) {
            return call_user_func_array([$this->new_class, $method], $args);
        }
        throw new \Exception('Method not found');
    }

    // public function test()
    // {
    //     echo 'c';
    // }
}

$a = new NewClass();
$test = 'test';
$a->$test(); // Output: a

$return_var = call_user_func([$a, $test]); // Output: a

$test2 = 'test2';
$return_val_2 = call_user_func([$a, $test2], 'b'); // Output: b

$a->$test2('this is easier');

$new_class_container = new NewClassContainer();

// if it has the method test, it will invoke from it, otherwise it will invoke it via the __call method
$new_class_container->$test(); // Output: a