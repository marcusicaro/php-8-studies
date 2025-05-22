<?php

require_once __DIR__ . "/dependency_injection_classes.php";
use popp\ch09\batch06\ApptEncoder\{Sea, Plains, Forest};

class InjectConstructor
{
    function __construct() {}
}

class TerrainFactory
{
    #[InjectConstructor(Sea::class, Plains::class, Forest::class)]
    public function __construct(
        private Sea $sea,
        private Plains $plains,
        private Forest $forest
    ) {}
    public function getSea(): Sea
    {
        return clone $this->sea;
    }
    public function getPlains(): Plains
    {
        return clone $this->plains;
    }
    public function getForest(): Forest
    {
        return clone $this->forest;
    }
}

class ObjectAssembler
{
    private array $components = [];
    public function __construct(string $conf)
    {
        $this->configure($conf);
    }
    private function configure(string $conf): void
    {
        $data = simplexml_load_file($conf);
        foreach ($data->class as $class) {
            $args = [];
            $name = (string)$class['name'];
            $resolvedname = $name;
            foreach ($class->arg as $arg) {
                $argclass = (string)$arg['inst'];
                $args[(int)$arg['num']] = $argclass;
            }
            if (isset($class->instance)) {
                if (isset($class->instance[0]['inst'])) {
                    $resolvedname = (string)$class->instance[0]['inst'];
                }
            }
            ksort($args);
            $this->components[$name] = function () use (
                $resolvedname,
                $args
            ) {
                $expandedargs = [];
                foreach ($args as $arg) {
                    $expandedargs[] = $this->getComponent($arg);
                }
                $rclass = new \ReflectionClass($resolvedname);
                return $rclass->newInstanceArgs($expandedargs);
            };
        }
    }
    
    public function getComponent(string $class): object
    {
        // create $inst -- our object instance
        // and a list of \ReflectionMethod objects
        if (isset($this->components[$class])) {
            // instance found in config
            $inst = $this->components[$class]();
            $rclass = new \ReflectionClass($inst::class);
            $methods = $rclass->getMethods();
        } else {
            $rclass = new \ReflectionClass($class);
            $methods = $rclass->getMethods();
            $injectconstructor = null;
            foreach ($methods as $method) {
                foreach (
                    $method->getAttributes(InjectConstructor::class)
                    as $attribute
                ) {
                    $injectconstructor = $attribute;
                    break;
                }
            }
            if (is_null($injectconstructor)) {
                $inst = $rclass->newInstance();
            } else {
                $constructorargs = [];
                foreach ($injectconstructor->getArguments() as $arg) {
                    $constructorargs[] = $this->getComponent($arg);
                }
                $inst = $rclass->newInstanceArgs($constructorargs);
            }
        }
        return $inst;
    }
}

$assembler = new ObjectAssembler("generating_objects/dependency_injection/dependency_injection_with_attributes.xml");
$terrainfactory = $assembler->getComponent(TerrainFactory::class);
$plains = $terrainfactory->getPlains(); // MarsPlains