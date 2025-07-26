<?php

namespace performing_and_representing_tasks\observer_pattern;

interface Observer
{
    public function update(Observable $observable): void;
}
class LoginAnalytics implements Observer
{
    public function update(Observable $observable): void
    {
        // not type safe!
        $status = $observable->getStatus();
        print __CLASS__ . ": doing something with status info\n";
    }
}

abstract class LoginObserver implements Observer
{
    private Login $login;
    public function __construct(Login $login)
    {
        $this->login = $login;
        $login->attach($this);
    }
    public function update(Observable $observable): void
    {
        if ($observable === $this->login) {
            $this->doUpdate($observable);
        }
    }
    abstract public function doUpdate(Login $login): void;
}

class SecurityMonitor extends LoginObserver
{
    public function doUpdate(Login $login): void
    {
        $status = $login->getStatus();
        if ($status[0] == Login::LOGIN_WRONG_PASS) {
            // send mail to sysadmin
            print __CLASS__ . ": sending mail to sysadmin\n";
        }
    }
}
// listing 11.32
class GeneralLogger extends LoginObserver
{
    public function doUpdate(Login $login): void
    {
        $status = $login->getStatus();
        // add login data to log
        print __CLASS__ . ": add login data to log\n";
    }
}
// listing 11.33
class PartnershipTool extends LoginObserver
{
    public function doUpdate(Login $login): void
    {
        $status = $login->getStatus();
        // check $ip address
        // set cookie if it matches a list
        print __CLASS__ . ": set cookie if it matches a list\n";
    }
}
