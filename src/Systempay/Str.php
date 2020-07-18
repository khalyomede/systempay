<?php

namespace Khalyomede\Systempay;

class Str
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function isValidUtf8(): bool
    {
        return mb_check_encoding($this->string, "UTF-8");
    }
}
