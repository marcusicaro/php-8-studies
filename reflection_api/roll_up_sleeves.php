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

class NewClass2 {
    const TESTING_THIS = 1;

    public function method()
    {
        echo 'b';
    }
}

class NewClassContainer extends NewClass2 {
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
}

$prodClass = new \ReflectionClass(NewClassContainer::class);
// print $prodClass;

// // comparing with var_dump
// $a = new NewClassContainer();
// var_dump($a);

class ClassInfo {
    public static function getData(\ReflectionClass $class): string
    {
    $details = "";
    $name = $class->getName();
    $details .= ($class->isUserDefined())?
    "$name is user defined\n": "" ;
    $details .= ($class->isInternal())?
    "$name is built-­in\n": "" ;
    $details .= ($class->isInterface())?
    "$name is interface\n": "" ;
    $details.=($class->isAbstract()) ?
    "$name is an abstract class\n" : "" ;
    $details .= ($class->isFinal())?
    "$name is a final class\n": "" ;
    $details .= ($class->isInstantiable()) ?
    "$name can be instantiated\n": "$name can not be instantiated\n" ;
    $details .= ($class->isCloneable())?
    "$name can be cloned\n": "$name can not be cloned\n" ;
    return $details;
    }
}

$classInfo = new ClassInfo();
print $classInfo->getData($prodClass);
