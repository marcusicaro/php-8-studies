<?php
class Product
{
    public function __construct(public string $name, public float $price) {}
}

class Mailer
{
    public function doMail(Product $product): void
    {
        print "mailing ({$product->name})\n";
    }
}

class ProcessSale
{
    private array $callbacks;
    public function registerCallback(callable $callback): void
    {
        $this->callbacks[] = $callback;
    }
    public function sale(Product $product): void
    {
        print "{$product->name}: processing \n";
        foreach ($this->callbacks as $callback) {
            call_user_func($callback, $product);
        }
    }
}

class Totalizer
{
    public static function warnAmount(): callable
    {
        return function (Product $product) {
            if ($product->price > 5) {
                print "reached high price: {$product->price}\n";
            }
        };
    }
}

class Totalizer2
{
    public static function warnAmount($amt): callable
    {
        $count = 0;
        return function ($product) use ($amt, &$count) {
            $count += $product->price;
            print "count: $count\n";
            if ($count > $amt) {
                print "high price reached: {$count}\n";
            }
        };
    }
}



// $a = new Product("shoes", 6);
// $b = fn($product) => print "logging {$product->name}\n";
// $process_sale = new ProcessSale();
// $process_sale->registerCallback(Totalizer2::warnAmount(8));
// $process_sale->sale($a);
// $process_sale->sale(new Product("coffee", 6));

// arrow function
function arrow()
{
    $markup = 3;
    $counter = fn(Product $product) => print "($product->name) marked up price: " .
        ($product->price + $markup) . "\n";
    $processor = new ProcessSale();
    $processor->registerCallback($counter);

    $processor->sale(new Product("shoes", 6));

    print "\n";
    $processor->sale(new Product("coffee", 6));
}

arrow();

// closure from callable
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

$totalizer3 = new Totalizer3();
$processor = new ProcessSale();
$processor->registerCallback($totalizer3->warnAmount(8));
$processor->sale(new Product("shoes", 6));
print "\n";
$processor->sale(new Product("coffee", 6));