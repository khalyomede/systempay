<?php

namespace Khalyomede\Systempay;

/**
 * This class helps normalizing error process accros different payment providers.
 *
 * For example, credit card providers and Elavon providers will have differents code for fraud, so this class helps to identify this kind of error no matter the payment provider.
 *
 * @see https://paiement.systempay.fr/doc/fr-FR/form-payment/standard-payment/vads-auth-result.html for more information.
 * @todo Implement Elavon Europe error codes
 * @todo Implement Amex Global error codes
 * @todo Implement GICC error codes
 */
class AuthorizationResult
{
    const CB_SUCCESS = "00";
    const CB_CONTACT_CARD_ISSUER = "02";
    const CB_INVALID_ACCEPTOR = "03";
    const CB_KEEP_CARD = "04";
    const CB_KEEP_CARD_WITH_SPECIAL_CONDITIONS = "07";
    const CB_DO_NOT_HONOR = "05";
    const CB_APPROVE_AFTER_IDENTIFICATION = "08";
    const CB_INVALID_TRANSACTION = "12";
    const CB_INVALID_AMOUNT = "13";
    const CB_INVALID_CARD_HOLDER_NUMBER = "14";
    const CB_UNKNOWN_CARD_ISSUER = "15";
    const CB_SHOPPER_CANCELED = "17";
    const CB_REPEAT_TRANSACTION_LATER = "19";
    const CB_RESPONSE_ERROR = "20";
    const CB_UNSUPPORTED_FILE_UPDATE = "24";
    const CB_RECORD_NOT_FOUND_IN_FILE = "25";
    const CB_DUPLICATED_RECORD = "26";
    const CB_ERROR_IN_EDIT_LIST_FILE = "27";
    const CB_FORBIDDEN_FILE_ACCESS = "28";
    const CB_UPDATE_NOT_POSSIBLE = "29";
    const CB_FORMAT_ERROR = "30";
    const CB_UNKNOWN_ACQUIRER_ORGANIZATION = "31";
    const CB_FRAUDULENT_EXPIRED_CARD_VALIDITY_DATE = "33";
    const CB_SUSPECTED_FRAUD = "34";
    const CB_EXPIRED_CARD_VALIDITY_DATE = "38";
    const CB_CARD_LOST = "41";
    const CB_STOLEN_CARD = "43";
    const CB_UNSUFFICIENT_CREDIT = "51";
    const CB_FRAUDULENT_EXPIRED_CARD_VALIDITY_DATE_2 = "54";
    const CB_WRONG_PIN = "55";
    const CB_UNKNOWN = "56";
    const CB_FRAUDULENT_TRANSACTION_NOT_PERMITTED_TO_SHOPPER = "57";
    const CB_TRANSACTION_NOT_PERMITTED_TO_SHOPPER = "58";
    const CB_SUSPECTED_FRAUD_2 = "59";
    const CB_CONTACT_CARD_ACQUIRER = "60";
    const CB_OUT_OF_LIMIT_WITHDRAWAL_AMOUNT = "61";
    const CB_SECURITY_RULES_NOT_RESPECTED = "63";
    const CB_NO_RESPONSE = "68";
    const CB_PIN_ATTEMPTS_EXCEEDED = "75";
    const CB_HOLDER_IN_OPPOSITION = "76";
    const CB_SYSTEM_SHUTDOWN = "90";
    const CB_CARD_ISSUER_UNREACHABLE = "91";
    const CB_DUPLICATED_TRANSACTION = "94";
    const CB_SYSTEM_MALFUNCTION = "96";
    const CB_GLOBAL_MONITORING_TIMEOUT_DEADLINE = "97";
    const CB_UNREACHABLE_SERVER = "98";
    const CB_INITIATOR_DOMAIN_INCIDENT = "99";

    /**
     * @var string
     */
    private $result;

    public function __construct(string $result)
    {
        $this->result = $result;
    }
    
    public function requiresToContactCardIssuer(): bool
    {
        return in_array($this->result, [
            self::CB_CONTACT_CARD_ISSUER,
        ]);
    }
    
    public function detectsSuccess(): bool
    {
        return in_array($this->result, [
            self::CB_SUCCESS,
        ]);
    }
    
    public function detectsInvalidAcceptor(): bool
    {
        return in_array($this->result, [
            self::CB_INVALID_ACCEPTOR,
        ]);
    }
    
    public function detectsInvalidTransaction(): bool
    {
        return in_array($this->result, [
            self::CB_INVALID_TRANSACTION,
        ]);
    }
    
    public function detectsInvalidAmount(): bool
    {
        return in_array($this->result, [
            self::CB_INVALID_AMOUNT,
        ]);
    }
    
    public function detectsInvalidCardHolderNumber(): bool
    {
        return in_array($this->result, [
            self::CB_INVALID_CARD_HOLDER_NUMBER,
        ]);
    }
    
    public function detectsShopperCanceled(): bool
    {
        return in_array($this->result, [
            self::CB_SHOPPER_CANCELED,
        ]);
    }
    
    public function detectsResponseError(): bool
    {
        return in_array($this->result, [
            self::CB_RESPONSE_ERROR,
            self::CB_UNSUPPORTED_FILE_UPDATE,
            self::CB_ERROR_IN_EDIT_LIST_FILE,
            self::CB_FORBIDDEN_FILE_ACCESS,
            self::CB_RECORD_NOT_FOUND_IN_FILE,
            self::CB_UPDATE_NOT_POSSIBLE,
            self::CB_FORMAT_ERROR,
            self::CB_DUPLICATED_RECORD,
            self::CB_NO_RESPONSE,
            self::CB_SYSTEM_SHUTDOWN,
            self::CB_CARD_ISSUER_UNREACHABLE,
            self::CB_DUPLICATED_RECORD,
            self::CB_SYSTEM_MALFUNCTION,
            self::CB_GLOBAL_MONITORING_TIMEOUT_DEADLINE,
            self::CB_UNREACHABLE_SERVER,
            self::CB_INITIATOR_DOMAIN_INCIDENT,
        ]);
    }
    
    public function detectsExpiredCard(): bool
    {
        return in_array($this->result, [
            self::CB_FRAUDULENT_EXPIRED_CARD_VALIDITY_DATE,
            self::CB_EXPIRED_CARD_VALIDITY_DATE,
        ]);
    }
    
    public function detectsUnsufficientProvision(): bool
    {
        return in_array($this->result, [
            self::CB_UNSUFFICIENT_CREDIT,
            self::CB_OUT_OF_LIMIT_WITHDRAWAL_AMOUNT,
        ]);
    }
    
    public function detectsWrongPing(): bool
    {
        return in_array($this->result, [
            self::CB_WRONG_PIN,
        ]);
    }
    
    public function detectsTransactionNotPermitted(): bool
    {
        return in_array($this->result, [
            self::CB_TRANSACTION_NOT_PERMITTED_TO_SHOPPER,
        ]);
    }
    
    public function detectsPinAttemptsExceeded(): bool
    {
        return in_array($this->result, [
            self::CB_PIN_ATTEMPTS_EXCEEDED,
        ]);
    }
    
    public function requiresToKeepTheCard(): bool
    {
        return in_array($this->result, [
            self::CB_KEEP_CARD,
            self::CB_KEEP_CARD_WITH_SPECIAL_CONDITIONS,
        ]);
    }
    
    public function requiresToNotHonor(): bool
    {
        return in_array($this->result, [
            self::CB_DO_NOT_HONOR,
        ]);
    }
    
    public function requiresToApproveAfterIdentification(): bool
    {
        return in_array($this->result, [
            self::CB_KEEP_CARD_WITH_SPECIAL_CONDITIONS,
        ]);
    }
    
    public function requiresToRepeatTransactionLater(): bool
    {
        return in_array($this->result, [
            self::CB_REPEAT_TRANSACTION_LATER,
        ]);
    }
    
    public function requiresToContactAcquirer(): bool
    {
        return in_array($this->result, [
            self::CB_CONTACT_CARD_ACQUIRER,
        ]);
    }
    
    public function isFraudulentResult(): bool
    {
        return in_array($this->result, [
            self::CB_INVALID_ACCEPTOR,
            self::CB_KEEP_CARD,
            self::CB_KEEP_CARD_WITH_SPECIAL_CONDITIONS,
            self::CB_DO_NOT_HONOR,
            self::CB_INVALID_TRANSACTION,
            self::CB_INVALID_AMOUNT,
            self::CB_INVALID_CARD_HOLDER_NUMBER,
            self::CB_UNKNOWN_CARD_ISSUER,
            self::CB_UNKNOWN_ACQUIRER_ORGANIZATION,
            self::CB_FRAUDULENT_EXPIRED_CARD_VALIDITY_DATE,
            self::CB_SUSPECTED_FRAUD,
            self::CB_SUSPECTED_FRAUD_2,
            self::CB_CARD_LOST,
            self::CB_STOLEN_CARD,
            self::CB_FRAUDULENT_EXPIRED_CARD_VALIDITY_DATE_2,
            self::CB_UNKNOWN,
            self::CB_FRAUDULENT_TRANSACTION_NOT_PERMITTED_TO_SHOPPER,
            self::CB_SECURITY_RULES_NOT_RESPECTED,
            self::CB_HOLDER_IN_OPPOSITION,
        ]);
    }
}
