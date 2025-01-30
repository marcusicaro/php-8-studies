<?php
class Checkout
{
    final public function totalize(): void
    {
    }
}

class IllegalCheckout extends Checkout
{
    public function totalize(): void
    {
    }
}