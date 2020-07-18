<?php

namespace Khalyomede\Systempay;

class HashAlgorithm
{
    const SHA1 = "sha1";
    const SHA256 = "sha256";
    const ALLOWED = [
        self::SHA1,
        self::SHA256,
    ];

    private $algorithm;
    
    public function __construct(string $algorithm)
    {
        $this->algorithm = $algorithm;
    }
    
    public function isSupported(): bool
    {
        return in_array($this->algorithm, hash_algos());
    }
    
    public function isAllowed(): bool
    {
        return in_array($this->algorithm, self::ALLOWED);
    }
    
    public static function getAllowedToString(): string
    {
        return implode(", ", self::ALLOWED);
    }
}
