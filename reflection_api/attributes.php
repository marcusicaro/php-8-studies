<?php

namespace popp\ch05\batch09;

#[info]
class Person
{
    private string $name;

    #[moreinfo]
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    #[ApiInfo("The 3 digit company identifier", "A five character department tag")]
    public function setInfo(int $companyid, string $department): void
    {
        $this->companyid = $companyid;
        $this->department = $department;
    }
}

$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setName");
$attrs = $rmeth->getAttributes();
foreach ($attrs as $attr) {
    print $attr->getName() . "\n";
}

$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setInfo");
$attrs = $rmeth->getAttributes();
foreach ($attrs as $attr) {
    print $attr->getName() . "\n";
    foreach ($attr->getArguments() as $arg) {
        print "- $arg\n";
    }
}


use Attribute;

#[Attribute]
class ApiInfo
{
    public function __construct(public string $compinfo, public string $depinfo) {}
}

// $rpers = new \ReflectionClass(Person::class);
// $rmeth = $rpers->getMethod("setInfo");
// $attrs = $rmeth->getAttributes();
// foreach ($attrs as $attr) {
//     print $attr->getName() . "\n";
//     $attrobj = $attr->newInstance();
//     print "- " . $attrobj->compinfo . "\n";
//     print "- " . $attrobj->depinfo . "\n";
// }


$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setInfo");
$attrs = $rmeth->getAttributes(ApiInfo::class);
$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setInfo");
$attrs = $rmeth->getAttributes(ApiInfo::class, \ReflectionAttribute::IS_INSTANCEOF);