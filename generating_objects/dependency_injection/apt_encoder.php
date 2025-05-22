<?php

namespace popp\ch09\batch06;

abstract class ApptEncoder
{
    abstract public function encode(): string;
}