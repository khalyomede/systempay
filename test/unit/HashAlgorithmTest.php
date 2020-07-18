<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\Systempay\HashAlgorithm;

final class HashAlgorithmTest extends TestCase
{
    public function testSha1IsSupported(): void
    {
        $this->assertTrue((new HashAlgorithm(HashAlgorithm::SHA1))->isSupported());
    }
    
    public function testSha1IsAllowed(): void
    {
        $this->assertTrue((new HashAlgorithm(HashAlgorithm::SHA1))->isAllowed());
    }
    
    public function testSha256IsSupported(): void
    {
        $this->assertTrue((new HashAlgorithm(HashAlgorithm::SHA256))->isSupported());
    }
    
    public function testSha256IsAllowed(): void
    {
        $this->assertTrue((new HashAlgorithm(HashAlgorithm::SHA256))->isAllowed());
    }
    
    public function testUnknownAlgorithmNotSupported(): void
    {
        $this->assertFalse((new HashAlgorithm("foo"))->isSupported());
    }

    public function testUnknownAlgorithmNotAllowed(): void
    {
        $this->assertFalse((new HashAlgorithm("foo"))->isAllowed());
    }
    
    public function testGetAllowedToStringReturnTheCorrectAlgorithms(): void
    {
        $this->assertEquals("sha1, sha256", (HashAlgorithm::getAllowedToString()));
    }
}
