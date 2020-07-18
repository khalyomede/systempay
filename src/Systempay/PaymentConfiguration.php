<?php

namespace Khalyomede\Systempay;

class PaymentConfiguration
{
    const SINGLE = "SINGLE";
    const MULTI = "MULTI";
    const ALLOWED = [
        self::SINGLE,
        self::MULTI,
    ];

    private $configuration;
    
    public function __construct(string $configuration)
    {
        $this->configuration = $configuration;
    }

    public function isAllowed(): bool
    {
        return in_array($this->configuration, self::ALLOWED);
    }

    public static function getAllowedToString(): string
    {
        return implode(", ", self::ALLOWED);
    }
}
