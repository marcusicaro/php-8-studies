<?php
require_once 'roll_up_sleeves.php';


$new_class_container = new NewClassContainer();
$new_class_container_classname = NewClassContainer::class;

$rmethod1 = new ReflectionMethod("$new_class_container_classname::__construct");

$rmethod2 = new \ReflectionMethod($new_class_container_classname, '__construct');

$rmethod3 = new \ReflectionMethod($new_class_container, 'method');

// print_r($rmethod1);
// print_r($rmethod2);
// print_r($rmethod3);

$prodclass = new \ReflectionClass(NewClassContainer::class);
$methods = $prodclass->getMethods();


foreach ($methods as $method) {
    print ClassInfo::methodData($method);
    print "\n";
}
