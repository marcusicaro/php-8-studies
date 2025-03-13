<?php

abstract class ParamHandler
{
    protected array $params = [];
    public function __construct(protected string $source) {}
    public function addParam(string $key, string $val): void
    {
        $this->params[$key] = $val;
    }
    public function getAllParams(): array
    {
        return $this->params;
    }
    public static function getInstance(string $filename): ParamHandler
    {
        if (preg_match("/\.xml$/i", $filename)) {
            return new XmlParamHandler($filename);
        }
        return new TextParamHandler($filename);
    }

    protected function openSource(string $flag): mixed
    {
        $fh = @fopen($this->source, $flag);
        if (empty($fh)) {
            throw new Exception("could not open: $this->source!");
        }
        return $fh;
    }


    abstract public function write(): void;
    abstract public function read(): void;
}

class XmlParamHandler extends ParamHandler
{

    public function write(): void
    {
        // write XML
        // using $this->params
/* /listing 06.05 */
        $fh = $this->openSource('w');
        fputs($fh, "<params>\n");
        foreach ($this->params as $key => $val) {
            fputs($fh, "\t<param>\n");
            fputs($fh, "\t\t<key>$key</key>\n");
            fputs($fh, "\t\t<val>$val</val>\n");
            fputs($fh, "\t</param>\n");
        }
        fputs($fh, "</params>\n");
        fclose($fh);
/* listing 06.05 */
    }

    public function read(): void
    {
        // read XML
        // and populate $this->params
/* /listing 06.05 */
        $el = @simplexml_load_file($this->source);
        if (empty($el)) {
            throw new Exception("could not parse $this->source");
        }
        foreach ($el->param as $param) {
            $this->params["$param->key"] = "$param->val";
        }
/* listing 06.05 */
    }
}

class TextParamHandler extends ParamHandler
{

    public function write(): void
    {
        // write text
        // using $this->params
/* /listing 06.06 */
        $fh = $this->openSource('w');
        foreach ($this->params as $key => $val) {
            fputs($fh, "$key:$val\n");
        }
        fclose($fh);
/* listing 06.06 */
    }

    public function read(): void
    {
        // read text
        // and populate $this->params
/* /listing 06.06 */
        $lines = file($this->source);
        foreach ($lines as $line) {
            $line = trim($line);
            list( $key, $val ) = explode(':', $line);
            $this->params[$key] = $val;
        }
/* listing 06.06 */
    }
}

$test = ParamHandler::getInstance(__DIR__ . "/params.xml");
$test->addParam("key1", "val1");
$test->addParam("key2", "val2");
$test->addParam("key3", "val3");
$test->write(); // writing in XML format

$test = ParamHandler::getInstance(__DIR__ . "/params.xml");
$test->read(); // reading in text format
$params = $test->getAllParams();
print_r($params);

if($test instanceof XmlParamHandler) {
    echo "XmlParamHandler\n";
} else {
    echo "TextParamHandler\n";
}