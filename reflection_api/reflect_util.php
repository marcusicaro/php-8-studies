<?php
require_once 'roll_up_sleeves.php';

class ReflectionUtil
{
    public static function getClassSource(\ReflectionClass $class): string
    {
        $path = $class->getFileName();
        $lines = @file($path);
        $from = $class->getStartLine();
        $to = $class->getEndLine();
        $len = $to - $from + 1;
        return implode(array_slice($lines, $from - 1, $len));
    }

    public static function getMethodSource(\ReflectionMethod $method): string
    {
        $path = $method->getFileName();
        $lines = @file($path);
        $from = $method->getStartLine();
        $to = $method->getEndLine();
        $len = $to - $from + 1;
        return implode(array_slice($lines, $from - 1, $len));
    }
}
// listing 05.67
// print ReflectionUtil::getClassSource(
//     new \ReflectionClass(NewClassContainer::class)
// );

$class = new \ReflectionClass(NewClassContainer::class);
$method = $class->getMethod('method');
print ReflectionUtil::getMethodSource($method);

