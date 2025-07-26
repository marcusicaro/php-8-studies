<?php
class Login implements \SplSubject
{
    private \SplObjectStorage $storage;
    // ...
    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }
    public function attach(\SplObserver $observer): void
    {
        $this->storage->attach($observer);
    }
    public function detach(\SplObserver $observer): void
    {
        $this->storage->detach($observer);
    }
    public function notify(): void
    {
        foreach ($this->storage as $obs) {
            $obs->update($this);
        }
    }
    // ...
}

abstract class LoginObserver implements \SplObserver
{
    public function __construct(private Login $login)
    {
        $login->attach($this);
    }
    public function update(\SplSubject $subject): void
    {
        if ($subject === $this->login) {
            $this->doUpdate($subject);
        }
    }
    abstract public function doUpdate(Login $login): void;
}
