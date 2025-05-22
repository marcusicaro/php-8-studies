<?php

class Settings
{
    public static string $COMMSTYPE = 'Mega';
}

class MegaCommsManager extends CommsManager
{
    public function sendMessage(string $message): void
    {
        echo "Sending message via Mega: $message\n";
    }
}

class BloggsCommsManager extends CommsManager
{
    public function sendMessage(string $message): void
    {
        echo "Sending message via Bloggs: $message\n";
    }
}

class CommsManager
{
    public function sendMessage(string $message): void
    {
        // Placeholder for sending message
    }
}

class AppConfig
{
    private static ?AppConfig $instance = null;
    private CommsManager $commsManager;
    private function __construct()
    {
        // will run once only
        $this->init();
    }
    private function init(): void
    {
        switch (Settings::$COMMSTYPE) {
            case 'Mega':
                $this->commsManager = new MegaCommsManager();
                break;
            default:
                $this->commsManager = new BloggsCommsManager();
        }
    }
    public static function getInstance(): AppConfig
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getCommsManager(): CommsManager
    {
        return $this->commsManager;
    }
}

$commsMgr = AppConfig::getInstance()->getCommsManager();
var_dump($commsMgr);