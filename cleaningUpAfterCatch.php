<?php
class XmlException extends \Exception
{
    public function __construct(private \LibXmlError $error)
    {
        $shortfile = basename($error->file);
        $msg = "[{$shortfile}, line {$error->line}, col {$error->column}] {$error->message}";
        $this->error = $error;
        parent::__construct($msg, $error->code);
    }

    public function getLibXmlError(): \LibXmlError
    {
        return $this->error;
    }
}
class FileException extends \Exception {}

class ConfException extends \Exception {}

class Conf
{
    private \SimpleXMLElement $xml;
    private \SimpleXMLElement $lastmatch;
    public function __construct(private string $file)
    {
        if (! file_exists($file)) {
            throw new FileException("file '{$file}' does not exist");
        }
        $this->xml = simplexml_load_file($file, null, LIBXML_NOERROR);
        if (! is_object($this->xml)) {
            throw new XmlException(libxml_get_last_error());
        }
        $matches = $this->xml->xpath("/conf");
        if (! count($matches)) {
            throw new ConfException("could not find root element: conf");
        }
    }
    public function write(): void
    {
        if (! is_writeable($this->file)) {
            throw new FileException("file '{$this->file}' is not writeable");
        }
        print "{$this->file} is apparently writeable\n";
        file_put_contents($this->file, $this->xml->asXML());
    }
    public function get(string $str): ?string
    {
        $matches = $this->xml->xpath("/conf/item[@name=\"$str\"]");
        if (count($matches)) {
            $this->lastmatch = $matches[0];
            return (string)$matches[0];
        }
        return null;
    }
    public function set(string $key, string $value): void
    {
        if (! is_null($this->get($key))) {
            $this->lastmatch[0] = $value;
            return;
        }
        $conf = $this->xml->conf;
        $this->xml->addChild('item', $value)->addAttribute('name', $key);
    }
}
$a = new Conf('conf.xml');
$a->write();

class Runner
{
    public static function init()
    {
        try {
            $fh = fopen("/tmp/log.txt", "a");
            fputs($fh, "start\n");
            $conf = new Conf(dirname(__FILE__) . "/conf.broken.xml");
            print "user: " . $conf->get('user') . "\n";
            print "host: " . $conf->get('host') . "\n";
            $conf->set("pass", "newpass");
            $conf->write();
            fputs($fh, "end\n");
            fclose($fh);
        } catch (FileException $e) {
            fputs($fh, "file exception\n");
            throw $e;
        } catch (XmlException $e) {
            fputs($fh, "xml exception\n");
            throw $e;
        } catch (ConfException $e) {
            fputs($fh, "conf exception\n");
            throw $e;
        } catch (\Exception $e) {
            fputs($fh, "general exception\n");
            throw $e;
        } finally {
            fputs($fh, "end\n");
            fclose($fh);
        }
    }
}
$b = new Runner();
$b->init();
