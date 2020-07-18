<?php

use Payum\ISO4217\ISO4217;
use PHPUnit\Framework\TestCase;
use Khalyomede\Systempay\Payment;
use Khalyomede\Systempay\Currency;
use Khalyomede\Systempay\ContextMode;
use Khalyomede\Systempay\HashAlgorithm;
use Khalyomede\Systempay\PaymentConfiguration;

final class PaymentTest extends TestCase
{
    public function testShouldSetTheDefaultAlgorithmToSha256(): void
    {
        $this->assertEquals(HashAlgorithm::SHA256, (new Payment)->getHashAlgorithm());
    }
    
    public function testShouldSetTheAlgorithm(): void
    {
        $algorithm = HashAlgorithm::SHA1;

        $this->assertEquals($algorithm, (new Payment)->setHashAlgorithm($algorithm)->getHashAlgorithm());
    }
    
    public function testShouldSetTheTotalAmount(): void
    {
        $totalAmount = 199.99;

        $this->assertEquals($totalAmount, (new Payment)->setTotalAmount($totalAmount)->getTotalAmount());
    }
    
    public function testShouldGetTheFormTotalAmountIfTheTotalAmountIsADecimal(): void
    {
        $this->assertEquals(19999, (new Payment)->setTotalAmount(199.99)->getFormTotalAmount());
    }
    
    public function testShouldGetTheFormTotalAmountIfTheTotalAmountIsAnInteger(): void
    {
        $totalAmount = 199;

        $this->assertEquals($totalAmount, (new Payment)->setTotalAmount($totalAmount)->getFormTotalAmount());
    }
    
    public function testSetSiteIdSetTheCorrectSiteId(): void
    {
        $siteId = "12345678";
        
        $this->assertEquals($siteId, (new Payment)->setSiteId($siteId)->getSiteId());
    }
    
    public function testSetTheCurrencyToEurByDefault(): void
    {
        $this->assertEquals((new ISO4217)->findByAlpha3(Currency::EUR)->getNumeric(), (new Payment)->getCurrencyNumericCode());
    }
    
    public function testShouldSetTheCorrectCurrencyNumericCode(): void
    {
        $expected = (new ISO4217)->findByAlpha3(Currency::EUR)->getNumeric();
        $actual = (new Payment)->setCurrency(Currency::EUR)->getCurrencyNumericCode();

        $this->assertEquals($expected, $actual);
    }

    public function testSetTheContextModeToTestByDefault(): void
    {
        $this->assertEquals(ContextMode::TEST, (new Payment)->getContextMode());
    }
    
    public function testSetTheContexteModeToTest(): void
    {
        $contextMode = ContextMode::TEST;

        $this->assertEquals($contextMode, (new Payment)->setContextMode($contextMode)->getContextMode());
    }

    public function testSetTheContextModeToProduction(): void
    {
        $contextMode = ContextMode::PRODUCTION;

        $this->assertEquals($contextMode, (new Payment)->setContextMode($contextMode)->getContextMode());
    }
    
    public function testSetSingleByDefaultInThePaymentConfiguration(): void
    {
        $this->assertEquals(PaymentConfiguration::SINGLE, (new Payment)->getPaymentConfiguration());
    }
    
    public function testSetThePaymentConfigurationSingle(): void
    {
        $configuration = PaymentConfiguration::SINGLE;

        $this->assertEquals($configuration, (new Payment)->setPaymentConfiguration($configuration)->getPaymentConfiguration());
    }
    
    public function testSetThePaymentConfigurationMulti(): void
    {
        $configuration = PaymentConfiguration::MULTI;

        $this->assertEquals($configuration, (new Payment)->setPaymentConfiguration($configuration)->getPaymentConfiguration());
    }
    
    public function testSetNowAsTheDefaultFormTransactionDate(): void
    {
        $now = new DateTime;

        $this->assertContains($now->format("YmdHi"), (new Payment)->getFormTransactionDate());
    }
    
    public function testSetTheDesiredTransactionDate(): void
    {
        $date = new DateTime;

        $this->assertEquals($date, (new Payment)->setTransactionDate($date)->getTransactionDate());
    }
    
    public function testSetARandomTransactionIdByDefault(): void
    {
        $this->assertEquals(6, strlen((new Payment)->getTransactionId()));
    }
    
    public function testSetTheTransactionId(): void
    {
        $transactionId = "a12b3c";
        
        $this->assertEquals($transactionId, (new Payment)->setTransactionid($transactionId)->getTransactionId());
    }
    
    public function testGetVersionShouldReturnV2(): void
    {
        $this->assertEquals("V2", (new Payment)->getVersion());
    }

    public function testKeyShouldBeSet(): void
    {
        $key = "testkey";

        $this->assertEquals($key, (new Payment)->setKey($key)->getKey());
    }

    public function testGetHtmlFormFieldsShouldReturnValidHtml(): void
    {
        $dom = (new DOMDocument)->loadHtml((new Payment)->setKey("foo")->getHtmlFormFields());

        $this->assertNotFalse($dom);
    }
    
    public function testReturnsTheFormUrl(): void
    {
        $this->assertEquals("https://paiement.systempay.fr/vads-payment/", (new Payment)->getFormUrl());
    }

    public function testThrowAnExceptionIfTheHashAlgorithmIsNotSupported(): void
    {
        $algorithm = "unknown";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Hash algorithm $algorithm is not supported");

        (new Payment)->setHashAlgorithm($algorithm);
    }
    
    public function testThrowAnExceptionIfTheHashAlgorithmIsNotAllowed(): void
    {
        $algorithm = "sha512";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Hash algorithm $algorithm is not allowed (allowed: sha1, sha256)");

        (new Payment)->setHashAlgorithm($algorithm);
    }
    
    public function testThrowsAnExceptionIfTheSiteIdIsLongerThan8Characters(): void
    {
        $siteId = "123456789";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The site id should not exceed 8 characters");
        
        (new Payment)->setSiteid($siteId);
    }
    
    public function testThrowsAnExceptionIfTheContextModeIsNotKnown(): void
    {
        $mode = "unknown";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Context mode $mode not allowed (allowed: TEST, PRODUCTION)");

        (new Payment)->setContextMode($mode);
    }
    
    public function testThrowAnExceptionIfTheCurrencyIsNotKnown(): void
    {
        $currency = "unknown";
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("unknown currency $currency");

        (new Payment)->setCurrency($currency);
    }
    
    public function testThrowsAnExceptionIfThePaymentConfigurationIsUnknown(): void
    {
        $configuration = "unknown";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Payment configuration $configuration not allowed (allowed: SINGLE, MULTI)");

        (new Payment)->setPaymentConfiguration($configuration);
    }
    
    public function testThrowsAnExceptionIfTheTransactionId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Transaction id must be 6 characters long");

        (new Payment)->setTransactionId("foo");
    }

    public function testThrowsExceptionIfTryingToGetTheHtmlFieldsWithoutProvidingTheKey(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Key must be set to compute the signature (use Payment::setKey())");

        (new Payment)->getHtmlFormFields();
    }
}
