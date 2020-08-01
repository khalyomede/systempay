<?php

namespace Khalyomede\Systempay;

use RuntimeException;

class HashAlgorithm
{
    const SHA1 = "sha1";
    const SHA256 = "sha256";
    const ALLOWED = [
        self::SHA1,
        self::SHA256,
    ];

    private $algorithm;
    
    /**
     * @var string
     */
    private $key;

    public function __construct(string $algorithm)
    {
        $this->algorithm = $algorithm;
        $this->key = "";
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

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }
    
    public function getKey(): string
    {
        return $this->key;
    }
    
    public function encode(string $data): string
    {
        if ($this->algorithm === HashAlgorithm::SHA256) {
            return base64_encode(hash_hmac($this->algorithm, $data, $this->key, true));
        } elseif ($this->algorithm === HashAlgorithm::SHA1) {
            return sha1($data);
        } else {
            throw new RuntimeException("Hash algorithm not supported: {$this->algorithm}");
        }
    }
}
