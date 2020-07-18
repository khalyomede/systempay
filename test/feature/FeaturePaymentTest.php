<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\Systempay\Payment;
use Khalyomede\Systempay\Currency;
use Khalyomede\Systempay\ContextMode;
use Khalyomede\Systempay\HashAlgorithm;
use Khalyomede\Systempay\PaymentConfiguration;

final class FeaturePaymentTest extends TestCase
{
    public function testCompletePaymentsWithRequiredFields(): void
    {
        $payment = new Payment;
        $payment->setKey("foo")
                ->setSiteId("12345678")
                ->setTotalAmount(199.99)
                ->setContextMode(ContextMode::TEST)
                ->setCurrency(Currency::EUR)
                ->setPaymentConfiguration(PaymentConfiguration::SINGLE) // One shot payment
                ->setTransactionDate(new DateTime("NOW"))
                ->setTransactionId("xrT15p")
                ->setHashAlgorithm(HashAlgorithm::SHA256);
            
        $this->assertNotEmpty($payment->getHtmlFormFields());
    }
}
