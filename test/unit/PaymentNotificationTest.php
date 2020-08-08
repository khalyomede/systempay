<?php

use Khalyomede\Systempay\AuthorizationResult;
use PHPUnit\Framework\TestCase;
use Khalyomede\Systempay\ContextMode;
use Khalyomede\Systempay\EventSource;
use Khalyomede\Systempay\Exception\MissingKeyException;
use Khalyomede\Systempay\HashAlgorithm;
use Khalyomede\Systempay\TransactionStatus;
use Khalyomede\Systempay\PaymentNotification;
use Khalyomede\Systempay\PaymentConfiguration;

final class PaymentNotificationTest extends TestCase
{
    public function testShouldGetThePaymentNotificationData(): void
    {
        $payload = $this->getPaymentResultPayload();

        $this->assertEquals($payload, (new PaymentNotification($payload))->getPaymentResultData());
    }
    
    public function testShouldGetTheKey(): void
    {
        $key = "123456";

        $this->assertEquals($key, (new PaymentNotification([]))->setKey($key)->getKey());
    }
    
    public function testShouldReturnTrueIfTheSignatureMatches(): void
    {
        $this->assertTrue((new PaymentNotification($this->getPaymentResultPayload()))->setKey("PLtsY7IpnYMBadb5")->hasValidSignature());
    }
    
    public function testShouldReturnTheSourceOfTheEvent(): void
    {
        $this->assertEquals(EventSource::PAYMENT, (new PaymentNotification($this->getPaymentResultPayload()))->getEventSource());
    }
    
    public function testReturnTheContextMode(): void
    {
        $this->assertEquals(ContextMode::TEST, (new PaymentNotification($this->getPaymentResultPayload()))->getContextMode());
    }
    
    public function testReturnTheTransactionStatus(): void
    {
        $this->assertEquals(TransactionStatus::AUTHORISED, (new PaymentNotification($this->getPaymentResultPayload()))->getTransactionStatus());
    }
    
    public function testReturnTheTransactionId(): void
    {
        $this->assertEquals("9c20f0", (new PaymentNotification($this->getPaymentResultPayload()))->getTransactionId());
    }
    
    public function testReturnThePaymentConfiguration(): void
    {
        $this->assertEquals(PaymentConfiguration::SINGLE, (new PaymentNotification($this->getPaymentResultPayload()))->getPaymentConfiguration());
    }
    
    public function testReturnTheNumberOfPaymentAttempts(): void
    {
        $this->assertEquals(1, (new PaymentNotification($this->getPaymentResultPayload()))->getNumberOfPaymentAttempt());
    }
    
    public function testReturnTheDateOfTransaction(): void
    {
        $expected = (new DateTime)->setTimestamp(20200801134711);

        $this->assertEquals($expected, (new PaymentNotification($this->getPaymentResultPayload()))->getTransactionDate());
    }
    
    public function testReturnTheNumberOfDaysBeforeThePaymentIsDepositedInBank(): void
    {
        $this->assertEquals(0, (new PaymentNotification($this->getPaymentResultPayload()))->getNumberOfDaysBeforeBanqueDeposit());
    }
    
    public function testReturnThePaymentAmount(): void
    {
        $this->assertEquals(199.99, (new PaymentNotification($this->getPaymentResultPayload()))->getPaymentAmount());
    }
    
    public function testReturnAuthResult(): void
    {
        $this->assertTrue((new PaymentNotification($this->getPaymentResultPayload()))->getAuthorizationResult()->detectsSuccess());
    }
    
    public function testReturnTheDefaultSha256HashAlgorithm(): void
    {
        $this->assertEquals(HashAlgorithm::SHA256, (new PaymentNotification($this->getPaymentResultPayload()))->getHashAlgorithm());
    }
    
    public function testReturnTheHashAlgorithmAfterBeingSet(): void
    {
        $hashAlgorithm = HashAlgorithm::SHA1;

        $this->assertEquals($hashAlgorithm, (new PaymentNotification($this->getPaymentResultPayload()))->setHashAlgorithm($hashAlgorithm)->getHashAlgorithm());
    }
    
    public function testThrowExceptionIfKeyIsMissingBeforeCheckingSignatureValid(): void
    {
        $this->expectException(MissingKeyException::class);
        $this->expectExceptionMessage("the key is required to check the signature");

        (new PaymentNotification($this->getPaymentResultPayload()))->hasValidSignature();
    }

    private function getPaymentResultPayload(array $override = []): array
    {
        return array_merge([
            'vads_amount' => '19999',
            'vads_auth_mode' => 'FULL',
            'vads_auth_number' => '3fe85c',
            'vads_auth_result' => '00',
            'vads_capture_delay' => '0',
            'vads_card_brand' => 'CB',
            'vads_card_number' => '497010XXXXXX0014',
            'vads_payment_certificate' => 'd6fdfcb76d0e23f0d8a2e205c5570348c1a9478e',
            'vads_ctx_mode' => 'TEST',
            'vads_currency' => '978',
            'vads_effective_amount' => '19999',
            'vads_effective_currency' => '978',
            'vads_site_id' => '49808206',
            'vads_trans_date' => '20200801134711',
            'vads_trans_id' => '9c20f0',
            'vads_trans_uuid' => '192ee2d72bfc4ab3a9b1230a4eb301cf',
            'vads_validation_mode' => '0',
            'vads_version' => 'V2',
            'vads_warranty_result' => 'NO',
            'vads_payment_src' => 'EC',
            'vads_sequence_number' => '1',
            'vads_contract_used' => '5249685',
            'vads_trans_status' => 'AUTHORISED',
            'vads_expiry_month' => '6',
            'vads_expiry_year' => '2021',
            'vads_bank_label' => 'Banque de démo et de l\'innovation',
            'vads_bank_product' => 'F',
            'vads_pays_ip' => 'FR',
            'vads_presentation_date' => '20200801134715',
            'vads_effective_creation_date' => '20200801134715',
            'vads_operation_type' => 'DEBIT',
            'vads_threeds_enrolled' => 'U',
            'vads_threeds_auth_type' => null,
            'vads_threeds_cavv' => null,
            'vads_threeds_eci' => null,
            'vads_threeds_xid' => null,
            'vads_threeds_cavvAlgorithm' => null,
            'vads_threeds_status' => null,
            'vads_threeds_sign_valid' => null,
            'vads_threeds_error_code' => '6',
            'vads_threeds_exit_status' => '6',
            'vads_risk_control' => 'BIN_FRAUD=OK;CARD_FRAUD=OK;IP_FRAUD=OK;SUSPECT_COUNTRY=OK;SUSPECT_IP_COUNTRY=OK',
            'vads_result' => '00',
            'vads_extra_result' => '00',
            'vads_card_country' => 'FR',
            'vads_language' => 'fr',
            'vads_brand_management' => '{"userChoice":false,"brandList":"CB|VISA","brand":"CB"}',
            'vads_hash' => '8f9ad1ae7ed93f952658031b8b356f8c8faed1564242b857ca1e496955195b5a',
            'vads_url_check_src' => 'PAY',
            'vads_action_mode' => 'INTERACTIVE',
            'vads_payment_config' => 'SINGLE',
            'vads_page_action' => 'PAYMENT',
            'signature' => 'Ur5s9uhuLMSxTS7X7qIHuUdvIiWTFk2FPfCXDxIRP0I=',
        ], $override);
    }
}
