<?php

abstract class Tile
{
    abstract public function getWealthFactor(): int;
}

// class Plains extends Tile
// {
//     private int $wealthfactor = 2;
//     public function getWealthFactor(): int
//     {
//         return $this->wealthfactor;
//     }
// }

// class DiamondPlains extends Plains
// {
//     public function getWealthFactor(): int
//     {
//         return parent::getWealthFactor() + 2;
//     }
// }

// class PollutedPlains extends Plains
// {
//     public function getWealthFactor(): int
//     {
//         return parent::getWealthFactor() - 4;
//     }
// }

// $tile = new PollutedPlains();
// print $tile->getWealthFactor();

// here begins the decorator pattern

class Plains extends Tile
{
    private int $wealthfactor = 2;
    public function getWealthFactor(): int
    {
        return $this->wealthfactor;
    }
}
// listing 10.24
abstract class TileDecorator extends Tile
{
    protected Tile $tile;
    public function construct(Tile $tile)
    {
        $this->tile = $tile;
    }
}

class DiamondDecorator extends TileDecorator
{
    public function getWealthFactor(): int
    {
        return $this->tile->getWealthFactor() + 2;
    }
}

class PollutionDecorator extends TileDecorator
{
    public function getWealthFactor(): int
    {
        return $this->tile->getWealthFactor() - 4;
    }
}

$tile = new Plains();
print $tile->getWealthFactor(); // 2
$tile = new DiamondDecorator(new Plains());
print $tile->getWealthFactor(); // 4
$tile = new PollutionDecorator(new DiamondDecorator(new Plains()));
print $tile->getWealthFactor(); // 0

class RequestHelper {}
// listing 10.31
abstract class ProcessRequest
{
    abstract public function process(RequestHelper $req): void;
}
// listing 10.32
class MainProcess extends ProcessRequest
{
    public function process(RequestHelper $req): void
    {
        print __CLASS__ . ": doing something useful with request\n";
    }
}
// listing 10.33
abstract class DecorateProcess extends ProcessRequest
{
    public function __construct(protected ProcessRequest $processrequest) {}
}

class LogRequest extends DecorateProcess
{
    public function process(RequestHelper $req): void
    {
        print __CLASS__ . ": logging request\n";
        $this->processrequest->process($req);
    }
}
class AuthenticateRequest extends DecorateProcess
{
    public function process(RequestHelper $req): void
    {
        print __CLASS__ . ": authenticating request\n";
        $this->processrequest->process($req);
    }
}
class StructureRequest extends DecorateProcess
{
    public function process(RequestHelper $req): void
    {
        print __CLASS__ . ": structuring request data\n";
        $this->processrequest->process($req);
    }
}


$process = new AuthenticateRequest(
    new StructureRequest(
        new LogRequest(
            new MainProcess()
        )
    )
);
$process->process(new RequestHelper());

// confusing

// function getProductFileLines(string $file): array
// {
//     return file($file);
// }
// function getProductObjectFromId(string $id, string $productname): Product
// {
//     // some kind of database lookup
//     return new Product($id, $productname);
// }
// function getNameFromLine(string $line): string
// {
//     if (preg_match("/.*-(.*)\s\d+/", $line, $array)) {
//         return str_replace('_', ' ', $array[1]);
//     }
//     return '';
// }
// function getIDFromLine($line): int|string
// {
//     if (preg_match("/^(\d{1,3})-/", $line, $array)) {
//         return $array[1];
//     }
//     return -1;
// }

class Product
{
    public string $id;
    public string $name;
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

class ProductFacade
{
    private array $products = [];
    public function __construct(private string $file)
    {
        $this->compile();
    }
    private function compile(): void
    {
        $lines = getProductFileLines($this->file);
        foreach ($lines as $line) {
            $id = getIDFromLine($line);
            $name = getNameFromLine($line);
            $this->products[$id] = getProductObjectFromID($id, $name);
        }
    }
    public function getProducts(): array
    {
        return $this->products;
    }
    public function getProduct(string $id): ?\Product
    {
        if (isset($this->products[$id])) {
            return $this->products[$id];
        }
        return null;
    }
}
