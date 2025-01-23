<?php

trait PriceUtilities
{
    private static $taxrate = 20;
    public static function calculateTax(float $price): float
    {
        return ((self::$taxrate / 100) * $price);
    }
}

trait IdentityTrait
{
    public function generateId(): string
    {
        return uniqid();
    }
}

trait TaxTools
{
    public function calculateTax(): float
    {
        return 222;
    }
}

interface IdentityObject
{
    public function generateId(): string;
}

class ShopProduct implements IdentityObject
{
    use PriceUtilities;
    use IdentityTrait;
    use TaxTools {
        TaxTools::calculateTax insteadof PriceUtilities;
        PriceUtilities::calculateTax as basicTax;
    }
}

class UtilityService
{
    use PriceUtilities;
    use IdentityTrait;
}
$a = new ShopProduct(12);
var_dump($a::basicTax(100));
// var_dump((new UtilityService())->calculateTax(100));
