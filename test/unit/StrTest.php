<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\Systempay\Str;

class StrTest extends TestCase
{
    public function testValidUtf8StringIsValid(): void
    {
        $this->assertTrue((new Str("foo"))->isValidUtf8());
    }

    public function testNonUtf8StringIsNotValid(): void
    {
        $this->assertFalse((new Str("Jes\xc3\u0192\xc2\xbas"))->isValidUtf8());
    }
}
