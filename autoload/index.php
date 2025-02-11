<?php


spl_autoload_register();
$writer = new Writer();


$basic = function (string $classname) {
    $file = __DIR__ . "/" . "{$classname}.php";
    if (file_exists($file)) {
        require_once($file);
    }
};

\spl_autoload_register($basic);
$blah = new Blah();
$blah->wave();

$underscores = function (string $classname) {
    $path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
    $path = __DIR__ . "/$path";
    if (file_exists("{$path}.php")) {
        require_once("{$path}.php");
    }
};
$namespaces = function (string $path) {
    if (preg_match('/\\\\/', $path)) {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = __DIR__ . "/$path";
    }

    if (file_exists("{$path}.php")) {
        echo "path: " . $path;
        require_once("{$path}.php");
    }

};


\spl_autoload_register($underscores);
\spl_autoload_register($namespaces);
$blah = new util_Blah();
$blah->wave();

$obj = new util\LocalPath();
$obj->wave();
