<?php

class Totalizer3
{
    private float $count = 0;
    private float $amt = 0;
    public function warnAmount(int $amt): callable
    {
        $this->amt = $amt;
        return \Closure::fromCallable([$this, "processPrice"]);
    }
    private function processPrice(Product $product): void
    {
        $this->count += $product->price;
        print "count: {$this->count}\n";
        if ($this->count > $this->amt) {
            print "high price reached: {$this->count}\n";
        }
    }
}
