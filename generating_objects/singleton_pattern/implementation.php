<?php

namespace generating_objects\singleton_pattern;

class Preferences
{
    private array $props = [];
    private static Preferences $instance;
    private function __construct() {}
    public function setProperty(string $key, string $val): void
    {
        $this->props[$key] = $val;
    }
    public function getProperty(string $key): string
    {
        return $this->props[$key];
    }
    public static function getInstance(): Preferences
    {
        if (empty(self::$instance)) {
            self::$instance = new Preferences();
        }
        return self::$instance;
    }
}


$pref = Preferences::getInstance();
$pref->setProperty("name", "matt");
echo $pref->getProperty("name");

unset($pref);

$pref2 = Preferences::getInstance();
print $pref2->getProperty("name");