<?php

require __DIR__ . "/../vendor/autoload.php";

use Khalyomede\Systempay\Payment;
use Khalyomede\Systempay\Currency;
use Khalyomede\Systempay\ContextMode;
use Khalyomede\Systempay\HashAlgorithm;
use Khalyomede\Systempay\PaymentConfiguration;

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

$fields = $payment->getHtmlFormFields();
$url = $payment->getFormUrl();

?>

<form method="POST" action="<?= $url ?>">
	<?= $fields ?>
	<button type="submit">Payer</button>
</form>
