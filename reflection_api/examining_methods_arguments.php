<?php
require_once 'roll_up_sleeves.php';

$classname = NewClassContainer::class;
$rparam1 = new \ReflectionParameter([$classname, "__construct"], 0);
$rparam2 = new \ReflectionParameter(
    [$classname, "__construct"],
    "test"
);

print_r($rparam1);
print_r($rparam2);
// $cd = new NewClassContainer();
// $rparam3 = new \ReflectionParameter([$cd, "__construct"], 1);
// $rparam4 = new \ReflectionParameter([$cd, "__construct"], "test");

$class = new \ReflectionClass(NewClassContainer::class);
$method = $class->getMethod("__construct");
$params = $method->getParameters();
foreach ($params as $param) {
    print ClassInfo::argData($param) . "\n";
}
