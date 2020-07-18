<?php

namespace Khalyomede\Systempay;

class ContextMode
{
    const TEST = "TEST";
    const PRODUCTION = "PRODUCTION";
    const ALLOWED = [
        self::TEST,
        self::PRODUCTION,
    ];
    
    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }

    public function isAllowed(): bool
    {
        return in_array($this->mode, self::ALLOWED);
    }

    public static function getAllowedToString(): string
    {
        return implode(", ", self::ALLOWED);
    }
}
