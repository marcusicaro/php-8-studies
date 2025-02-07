<?php
class ShopProduct
{
    public const AVAILABLE      = 0;
    public const OUT_OF_STOCK   = 1;
/* /listing 04.07 */
    public int $status;

    private string $title;
    private string $producerMainName;
    private string $producerFirstName;
    protected float|int $price;
    private int $discount = 0;

/* listing 04.05 */

// ShopProduct class...

    private int $id = 0;
    // ...

/* /listing 04.05 */

    public function __construct(
        string $title,
        string $firstName,
        string $mainName,
        int|float $price
    ) {
        $this->title             = $title;
        $this->producerFirstName = $firstName;
        $this->producerMainName  = $mainName;
        $this->price             = $price;
    }

/* listing 04.05 */
    public function setID(int $id): void
    {
        $this->id = $id;
    }
    // ...

/* /listing 04.05 */
    public function getProducerFirstName(): string
    {
        return $this->producerFirstName;
    }

    public function getProducerMainName(): string
    {
        return $this->producerMainName;
    }

    public function setDiscount(int $num): void
    {
        $this->discount = $num;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return ($this->price - $this->discount);
    }

    public function getProducer(): string
    {
        return "{$this->producerFirstName}" .
               " {$this->producerMainName}";
    }

    public function getSummaryLine(): string
    {
        $base  = "$this->title ( $this->producerMainName, ";
        $base .= "$this->producerFirstName )";
        return $base;
    }

/* listing 04.05 */
    public static function getInstance(int $id, \PDO $pdo): ShopProduct
    {
        $stmt = $pdo->prepare("select * from products where id=?");
        $result = $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (empty($row)) {
            return null;
        }

        if ($row['type'] == "book") {
            $product = new BookProduct(
                $row['title'],
                $row['firstname'],
                $row['mainname'],
                (float) $row['price'],
                (int) $row['numpages']
            );
        } elseif ($row['type'] == "cd") {
            $product = new CdProduct(
                $row['title'],
                $row['firstname'],
                $row['mainname'],
                (float) $row['price'],
                (int) $row['playlength']
            );
        } else {
            $firstname = (is_null($row['firstname'])) ? "" : $row['firstname'];
            $product = new ShopProduct(
                $row['title'],
                $firstname,
                $row['mainname'],
                (float) $row['price']
            );
        }
        $product->setId((int) $row['id']);
        $product->setDiscount((int) $row['discount']);
        return $product;
    }
/* /listing 04.05 */
}



/* listing 04.11 */
/* listing 04.09 */
abstract class ShopProductWriter
{
    protected array $products = [];

    public function addProduct(ShopProduct $shopProduct): void
    {
        $this->products[] = $shopProduct;
    }
/* /listing 04.09 */

    abstract public function write(): void;
/* listing 04.09 */
}

class XmlProductWriter extends ShopProductWriter
{

    public function write(): void
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement("products");
        foreach ($this->products as $shopProduct) {
            $writer->startElement("product");
            $writer->writeAttribute("title", $shopProduct->getTitle());
            $writer->startElement("summary");
            $writer->text($shopProduct->getSummaryLine());
            $writer->endElement(); // summary
            $writer->endElement(); // product
        }
        $writer->endElement(); // products
        $writer->endDocument();
        print $writer->flush();
    }
}

$xml = new XmlProductWriter();
$xml->addProduct(new ShopProduct("title", "firstName", "mainName", 12));
$xml->write();
class TextProductWriter extends ShopProductWriter
{
public function write(): void
{
$str = "PRODUCTS:\n";
foreach ($this->products as $shopProduct) {
$str .= $shopProduct->getSummaryLine() . "\n";
}
print $str;
}
}