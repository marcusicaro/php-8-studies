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
