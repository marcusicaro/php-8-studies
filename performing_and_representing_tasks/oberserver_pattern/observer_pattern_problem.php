<?php

class Login
{
    public const LOGIN_USER_UNKNOWN = 1;
    public const LOGIN_WRONG_PASS = 2;
    public const LOGIN_ACCESS = 3;
    private array $status = [];
    public function handleLogin(string $user, string $pass, string $ip): bool
    {
        $isvalid = false;
        switch (rand(1, 3)) {
            case 1:
                $this->setStatus(self::LOGIN_ACCESS, $user, $ip);
                $isvalid = true;
                break;
            case 2:
                $this->setStatus(self::LOGIN_WRONG_PASS, $user, $ip);
                $isvalid = false;
                break;
            case 3:
                $this->setStatus(self::LOGIN_USER_UNKNOWN, $user, $ip);
                $isvalid = false;
                break;
        }
        // Logger::logIP($user, $ip, $this->getStatus());
        print "returning " . (($isvalid) ? "true" : "false") . "\n";
        // if (! $isvalid) {
        //     Notifier::mailWarning(
        //         $user,
        //         $ip,
        //         $this->getStatus()
        //     );
        // }
        return $isvalid;
    }
    private function setStatus(int $status, string $user, string $ip): void
    {
        $this->status = [$status, $user, $ip];
    }
    public function getStatus(): array
    {
        return $this->status;
    }
}
