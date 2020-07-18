<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\Systempay\PaymentConfiguration;

final class PaymentConfigurationTest extends TestCase
{
    public function testSingleIsAllowed(): void
    {
        $this->assertTrue((new PaymentConfiguration(PaymentConfiguration::SINGLE))->isAllowed());
    }

    public function testMultiIsAllowed(): void
    {
        $this->assertTrue((new PaymentConfiguration(PaymentConfiguration::MULTI))->isAllowed());
    }

    public function testUnknownAlgorithmNotAllowed(): void
    {
        $this->assertFalse((new PaymentConfiguration("foo"))->isAllowed());
    }

    public function testGetAllowedToStringReturnTheCorrectAlgorithms(): void
    {
        $this->assertEquals("SINGLE, MULTI", (PaymentConfiguration::getAllowedToString()));
    }
}
