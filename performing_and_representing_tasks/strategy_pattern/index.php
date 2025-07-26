<?php

abstract class Question
{
    public function __construct(protected string $prompt, protected Marker
    $marker) {}
    public function mark(string $response): bool
    {
        return $this->marker->mark($response);
    }
}

class TextQuestion extends Question
{
    // do text question specific things
}
// listing 11.16
class AVQuestion extends Question
{
    // do audiovisual question specific things
}

abstract class Marker
{
    public function __construct(protected string $test) {}
    abstract public function mark(string $response): bool;
}
// listing 11.18
class MarkLogicMarker extends Marker
{
    private MarkParse $engine;
    public function __construct(string $test)
    {
        parent::__construct($test);
        $this->engine = new MarkParse($test);
    }
    public function mark(string $response): bool
    {
        return $this->engine->evaluate($response);
    }
}
// listing 11.19
class MatchMarker extends Marker
{
    public function mark(string $response): bool
    {
        return ($this->test == $response);
    }
}
// listing 11.20
class RegexpMarker extends Marker
{
    public function mark(string $response): bool
    {
        return (preg_match("$this->test", $response) === 1);
    }
}

class MarkParse
{
    private string $test;
    public function __construct(string $test)
    {
        $this->test = $test;
    }
    public function evaluate(string $response): bool
    {
        // Simulate evaluation logic
        return ($this->test === '$input equals "five"' && $response === "five");
    }
}


$markers = [
    new RegexpMarker("/f.ve/"),
    new MatchMarker("five"),
    new MarkLogicMarker('$input equals "five"')
];

foreach ($markers as $marker) {
    print get_class($marker) . "\n";
    $question = new TextQuestion("how many beans make five", $marker);
    foreach (["five", "four"] as $response) {
        print " response: $response: ";
        if ($question->mark($response)) {
            print "well done\n";
        } else {
            print "never mind\n";
        }
    }
}
