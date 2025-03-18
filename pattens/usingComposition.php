<?php

require_once __DIR__ . '/compositionAndInheritance.php';

// abstract class Lesson
// {
//     public function __construct(private int $duration, private CostStrategy $costStrategy) {}
//     public function cost(): int
//     {
//         return $this->costStrategy->cost($this);
//     }
//     public function chargeType(): string
//     {
//         return $this->costStrategy->chargeType();
//     }
//     public function getDuration(): int
//     {
//         return $this->duration;
//     }
// }

abstract class CostStrategy
{
    abstract public function cost(Lesson $lesson): int;
    abstract public function chargeType(): string;
}

class TimedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson): int
    {
        return $lesson->getDuration() * 5;
    }
    public function chargeType(): string
    {
        return "hourly rate";
    }
}

class FixedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson): int
    {
        return 30;
    }
    public function chargeType(): string
    {
        return "fixed rate";
    }
}

class RegistrationMgr
{
    public function register(Lesson $lesson): void
    {
        // do something with this Lesson
        // now tell someone
        $notifier = Notifier::getNotifier();
        $notifier->inform("new lesson: cost ({$lesson->cost()})");
    }
}

abstract class Notifier
{
    public static function getNotifier(): Notifier
    {
        // acquire concrete class according to
        // configuration or other logic
        if (rand(1, 2) === 1) {
            return new MailNotifier();
        } else {
            return new TextNotifier();
        }
    }
    abstract public function inform($message): void;
}

class MailNotifier extends Notifier
{
    public function inform($message): void
    {
        print "MAIL notification: {$message}\n";
    }
}
// listing 08.16
class TextNotifier extends Notifier
{
    public function inform($message): void
    {
        print "TEXT notification: {$message}\n";
    }
}

$lessons1 = new Seminar(4);
$lessons2 = new Lecture(4);
$mgr = new RegistrationMgr();
$mgr->register($lessons1);
$mgr->register($lessons2);