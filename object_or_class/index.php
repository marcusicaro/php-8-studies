<?php
require_once("Test.php");

$myObj = new \test\Test();

var_dump(get_class($myObj) === 'test\Test');
var_dump($myObj instanceof test\Test);

use test\Test as t;

print  t\Test::class . ' \n ';

print_r(get_class_methods($myObj));
print_r(get_class_methods('test\Test'));

$method = 'method';
if (in_array($method, get_class_methods($myObj))) {
    print $myObj->$method(); // invoke the method
}

if(is_callable([$myObj, $method])) {
    print $myObj->$method(); // invoke the method
}