<?php

interface Chargeable
{
	public function getPrice(): float;
}

class ShopProduct implements Chargeable
{
    // ...
    protected float $price;
    // ...
/* /listing 04.16 */
    public function __construct(float $price)
    {
        $this->price = $price;
    }
/* listing 04.16 */

    public function getPrice(): float
    {
        return $this->price;
    }
    // ...
}

class Shipping implements Chargeable
{
public function __construct(private float $price)
{
}
public function getPrice(): float
{
return $this->price;
}
}

class Cart {
private $items = [];
    public function addChargeableItem(Chargeable $item)
{
    $this->items[] = $item;
}
    public function getItems(): array
{
    return $this->items;
}
}

$cart = new Cart();
$cart->addChargeableItem(new ShopProduct(1.95));
$cart->addChargeableItem(new Shipping(3.95));

var_dump($cart->getItems());