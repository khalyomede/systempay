<?php

namespace Khalyomede\Systempay;

use DateTime;
use Khalyomede\Systempay\HashAlgorithm;
use Khalyomede\Systempay\Exception\MissingKeyException;

/**
 * This class is used to process POST data coming from the Systempay server after the shopper validated a payment.
 * It has been tested using the Systempay Form API (not the REST API), for single payments.
 */
class PaymentNotification
{
    /**
     * @var array
     */
    private $paymentResultData;
    
    /**
     * The test or production key.
     *
     * @var string
     */
    private $key;
    
    /**
     * @var string
     */
    private $hashAlgorithm;

    public function __construct(array $paymentResultData)
    {
        $this->paymentResultData = $paymentResultData;
        $this->key = "";
        $this->hashAlgorithm = HashAlgorithm::SHA256;
    }
    
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }
    
    public function setHashAlgorithm(string $algorithm): self
    {
        $this->hashAlgorithm = $algorithm;

        return $this;
    }
    
    public function getHashAlgorithm(): string
    {
        return $this->hashAlgorithm;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPaymentResultData(): array
    {
        return $this->paymentResultData;
    }

    /**
     * @throws MissingKeyException If the key has not been set.
     */
    public function hasValidSignature(): bool
    {
        $signature = (new HashAlgorithm($this->hashAlgorithm))
            ->setKey($this->key)
            ->encode($this->getConcatenatedSortedVadsFields());

        return $signature === $this->paymentResultData["signature"];
    }
    
    public function getEventSource(): string
    {
        return $this->paymentResultData["vads_url_check_src"];
    }
    
    public function getContextMode(): string
    {
        return $this->paymentResultData["vads_ctx_mode"];
    }
    
    public function getTransactionStatus(): string
    {
        return $this->paymentResultData["vads_trans_status"];
    }
    
    public function getTransactionId(): string
    {
        return $this->paymentResultData["vads_trans_id"];
    }
    
    public function getTransactionDate(): DateTime
    {
        return (new DateTime)->setTimestamp((int) $this->paymentResultData["vads_trans_date"]);
    }
    
    public function getPaymentConfiguration(): string
    {
        return $this->paymentResultData["vads_payment_config"];
    }
    
    public function getNumberOfPaymentAttempt(): int
    {
        return (int) $this->paymentResultData["vads_sequence_number"];
    }
    
    public function getNumberOfDaysBeforeBanqueDeposit(): int
    {
        return (int) $this->paymentResultData["vads_capture_delay"];
    }
    
    public function getPaymentAmount(): float
    {
        return ((float) $this->paymentResultData["vads_amount"]) / 100;
    }
    
    public function getAuthorizationResult(): string
    {
        return $this->paymentResultData["vads_auth_result"];
    }

    /**
     * Returns all the fields that start with "vads" in the payment result data.
     */
    private function getSortedVadsFields(): array
    {
        $paymentResultData = $this->paymentResultData;

        ksort($paymentResultData);

        $paymentResultData = array_filter($paymentResultData, function ($key) {
            return preg_match("/^vads/", $key) === 1;
        }, ARRAY_FILTER_USE_KEY);

        return $paymentResultData;
    }

    private function getConcatenatedSortedVadsFields(): string
    {
        if (!$this->keyisFilled()) {
            throw new MissingKeyException("the key is required to check the signature");
        }
        
        $concatenatedSortedFields = implode("+", $this->getSortedVadsFields());
        
        return "$concatenatedSortedFields+{$this->key}";
    }
    
    private function keyIsFilled()
    {
        return !empty(trim($this->key));
    }
}
