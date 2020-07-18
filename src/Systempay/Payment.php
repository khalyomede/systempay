<?php

namespace Khalyomede\Systempay;

use DateTime;
use Exception;
use RuntimeException;
use Payum\ISO4217\ISO4217;
use InvalidArgumentException;
use Khalyomede\Systempay\Str;
use Khalyomede\Systempay\Currency;
use Khalyomede\Systempay\ContextMode;
use Khalyomede\Systempay\HashAlgorithm;
use Khalyomede\Systempay\PaymentConfiguration;

class Payment
{
    const FORM_URL = "https://paiement.systempay.fr/vads-payment/";
    const ACTION_MODE = "INTERACTIVE";
    const PAGE_ACTION = "PAYMENT";
    const MAX_SITE_ID_LENGTH = 8;
    const TRANSACTION_DATE_FORMAT = "YmdHis";
    const PROTOCOL_VERSION = "V2";

    private $hashAlgorithm;
    private $totalAmount;
    private $siteId;
    private $contextMode;
    private $currencyNumericCode;
    private $transactionDate;
    private $transactionId;
    private $key;
    private $paymentConfiguration;

    public function __construct()
    {
        $this->hashAlgorithm = HashAlgorithm::SHA256;
        $this->totalAmount = 0;
        $this->siteId = "";
        $this->contextMode = ContextMode::TEST;
        $this->currencyNumericCode = (new ISO4217)->findByAlpha3(Currency::EUR)->getNumeric();
        $this->paymentConfiguration = PaymentConfiguration::SINGLE;
        $this->setTransactionDate(new DateTime);
        $this->setRandomTransactionId();
        $this->key = "";
    }

    /**
     * @throws InvalidArgumentException If the algorithm is not supported.
     * @throws InvalidArgumentException If the algorithm is not either sha1 or sha256.
     */
    public function setHashAlgorithm(string $algorithm): self
    {
        $hashAlgorithm = new HashAlgorithm($algorithm);

        if (!$hashAlgorithm->isSupported()) {
            throw new InvalidArgumentException("Hash algorithm $algorithm is not supported");
        }

        if (!$hashAlgorithm->isAllowed()) {
            throw new InvalidArgumentException("Hash algorithm $algorithm is not allowed (allowed: {$hashAlgorithm->getAllowedToString()})");
        }

        $this->hashAlgorithm = $algorithm;

        return $this;
    }
    
    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }
    
    public function setSiteId(string $siteId): self
    {
        if (strlen($siteId) > self::MAX_SITE_ID_LENGTH) {
            throw new InvalidArgumentException("The site id should not exceed 8 characters");
        }
        
        if (!(new Str($siteId))->isValidUtf8()) {
            throw new InvalidArgumentException("The site id must be a valid UTF-8 string");
        }

        $this->siteId = $siteId;

        return $this;
    }
    
    public function setContextMode(string $mode): self
    {
        $contextMode = new ContextMode($mode);

        if (!$contextMode->isAllowed()) {
            throw new InvalidArgumentException("Context mode $mode not allowed (allowed: {$contextMode->getAllowedToString()})");
        }

        $this->contextMode = $mode;

        return $this;
    }
    
    public function setCurrency(string $currency): self
    {
        try {
            $this->currencyNumericCode = (new ISO4217)->findByAlpha3($currency)->getNumeric();
        } catch (InvalidArgumentException $exception) {
            throw new InvalidArgumentException("unknown currency $currency");
        }
        
        return $this;
    }
    
    public function setPaymentConfiguration(string $configuration): self
    {
        $paymentConfiguration = new PaymentConfiguration($configuration);

        if (!$paymentConfiguration->isAllowed()) {
            throw new InvalidArgumentException("Payment configuration $configuration not allowed (allowed: {$paymentConfiguration->getAllowedToString()})");
        }

        $this->paymentConfiguration = $configuration;
        
        return $this;
    }
    
    public function setTransactionDate(DateTime $date): self
    {
        $this->transactionDate = $date;
        
        return $this;
    }
    
    public function setRandomTransactionId(): self
    {
        $transactionId = bin2hex(openssl_random_pseudo_bytes(3));

        if ($transactionId === false) {
            throw new RuntimeException("Failed to generate a random transaction id");
        }

        $this->transactionId = $transactionId;

        return $this;
    }

    public function setTransactionId(string $transactionId): self
    {
        if (strlen($transactionId) !== 6) {
            throw new InvalidArgumentException("Transaction id must be 6 characters long");
        }
        
        if (!(new Str($transactionId))->isValidUtf8()) {
            throw new InvalidArgumentException("Transaction id must be a valid UTF-8 string");
        }

        $this->transactionId = $transactionId;

        return $this;
    }
    
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getHashAlgorithm(): string
    {
        return $this->hashAlgorithm;
    }
    
    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }
    
    public function getFormTotalAmount(): int
    {
        [$digits, $digitsAfterComa] = explode(".", $this->totalAmount);
        $numberOfDigitsAfterComa = strlen($digitsAfterComa);

        return (int) ($this->totalAmount * (10 ** $numberOfDigitsAfterComa));
    }
    
    public function getSiteId(): string
    {
        return $this->siteId;
    }
    
    public function getContextMode(): string
    {
        return $this->contextMode;
    }
    
    public function getCurrencyNumericCode(): int
    {
        return $this->currencyNumericCode;
    }
    
    public function getPaymentConfiguration(): string
    {
        return $this->paymentConfiguration;
    }
    
    public function getTransactionDate(): DateTime
    {
        return $this->transactionDate;
    }
    
    public function getFormTransactionDate(): string
    {
        return $this->transactionDate->format(self::TRANSACTION_DATE_FORMAT);
    }
    
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
    
    public function getVersion(): string
    {
        return self::PROTOCOL_VERSION;
    }

    public function getActionMode(): string
    {
        return self::ACTION_MODE;
    }
    
    public function getPageAction(): string
    {
        return self::PAGE_ACTION;
    }
    
    public function getHtmlFormFields(): string
    {
        $fields = $this->getFields();
        $fields["signature"] = $this->getSignature();
        
        $htmlFormFields = array_map(function ($key, $value) {
            $value = addslashes($value);

            return "<input type='hidden' name='$key' value='$value' />";
        }, array_keys($fields), array_values($fields));
        
        return implode("\n", $htmlFormFields);
    }
    
    public function getKey(): string
    {
        return $this->key;
    }

    public function getFormUrl(): string
    {
        return self::FORM_URL;
    }

    private function getSignature(): string
    {
        if (empty($this->key)) {
            throw new Exception("Key must be set to compute the signature (use Payment::setKey())");
        }

        $fields = $this->getFields();
        $fields["z_key"] = $this->getKey();
        $fieldsString = implode("+", $fields);
        $hashAlgorithm = $this->getHashAlgorithm();

        if ($hashAlgorithm === HashAlgorithm::SHA256) {
            return base64_encode(hash_hmac($hashAlgorithm, $fieldsString, $this->getKey(), true));
        } elseif ($hashAlgorithm === HashAlgorithm::SHA1) {
            return sha1($fieldsString);
        } else {
            throw new RuntimeException("Hash algorithm not supported: $hashAlgorithm");
        }
    }

    private function getFields(): array
    {
        $fields = [
            "vads_action_mode" => $this->getActionMode(),
            "vads_amount" => $this->getFormTotalAmount(),
            "vads_ctx_mode" => $this->getContextMode(),
            "vads_currency" => $this->getCurrencyNumericCode(),
            "vads_page_action" => $this->getPageAction(),
            "vads_payment_config" => $this->getPaymentConfiguration(),
            "vads_site_id" => $this->getSiteId(),
            "vads_trans_date" => $this->getFormTransactionDate(),
            "vads_trans_id" => $this->getTransactionId(),
            "vads_version" => $this->getVersion(),
        ];

        ksort($fields);

        return $fields;
    }
}
