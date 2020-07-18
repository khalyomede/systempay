<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\Systempay\ContextMode;

final class ContextModeTest extends TestCase
{
    public function testTESTisAllowed(): void
    {
        $this->assertTrue((new ContextMode(ContextMode::TEST))->isAllowed());
    }

    public function testPRODUCTIONisAllowed(): void
    {
        $this->assertTrue((new ContextMode(ContextMode::PRODUCTION))->isAllowed());
    }

    public function testUnknownAlgorithmNotAllowed(): void
    {
        $this->assertFalse((new ContextMode("foo"))->isAllowed());
    }

    public function testGetAllowedToStringReturnTheCorrectAlgorithms(): void
    {
        $this->assertEquals("TEST, PRODUCTION", (ContextMode::getAllowedToString()));
    }
}
