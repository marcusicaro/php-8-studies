<?php

require_once __DIR__ . "/apt_encoder.php";
require_once __DIR__ . "/blogs_apt_encoder.php";
use popp\ch09\batch06\ApptEncoder;
use popp\ch09\batch06\BloggsApptEncoder;

class AppointmentMaker
{
    public function makeAppointment(): string
    {
        $encoder = new BloggsApptEncoder();
        return $encoder->encode();
    }
}

class AppointmentMaker2
{
    public function __construct(private ApptEncoder $encoder) {}
    public function makeAppointment(): string
    {
        return $this->encoder->encode();
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
            $name = (string)$class['name'];
            $resolvedname = $name;
            if (isset($class->instance)) {
                if (isset($class->instance[0]['inst'])) {
                    $resolvedname = (string)$class->instance[0]['inst'];
                }
            }
            $this->components[$name] = function () use ($resolvedname) {
                $rclass = new \ReflectionClass($resolvedname);
                return $rclass->newInstance();
            };
        }
    }
    public function getComponent(string $class): object
    {
        if (isset($this->components[$class])) {
            $inst = $this->components[$class]();
        } else {
            $rclass = new \ReflectionClass($class);
            $inst = $rclass->newInstance();
        }
        return $inst;
    }
}

$assembler = new ObjectAssembler(__DIR__ . "/objects.xml");
$encoder = $assembler->getComponent(ApptEncoder::class);
$apptmaker = new AppointmentMaker2($encoder);
$out = $apptmaker->makeAppointment();
print $out;