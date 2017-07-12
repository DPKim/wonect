<?php

$type = filter_input(INPUT_POST, 'index');
$pe_id = filter_input(INPUT_POST, 'p_id');

if (!isset($type)) {
    $type = 1;
}

require __DIR__ . '/../bootstrap.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

// ### Payer
// A resource representing a Payer that funds a payment
// For paypal account payments, set payment method
// to 'paypal'.
$payer = new Payer();
$payer->setPaymentMethod("paypal");

switch ($type) {
    case 1:
        $item1 = new Item();
        $item1->setName('5 GOLD')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku("PP_5G")
                ->setPrice(5);
        break;
    case 2:
        $item1 = new Item();
        $item1->setName('10 GOLD')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku("PP_10G")
                ->setPrice(9.50);
        break;
    case 3:
        $item1 = new Item();
        $item1->setName('50 GOLD')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku("PP_50G")
                ->setPrice(45.50);
        break;
    case 4:
        $item1 = new Item();
        $item1->setName('100 GOLD')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku("PP_100G")
                ->setPrice(88);
        break;
}
//
$itemList = new ItemList();
$itemList->setItems(array($item1));
//
switch ($type) {
    case 1:
        $details = new Details();
        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal(5);
        //
        $amount = new Amount();
        $amount->setCurrency("USD")
                ->setTotal(5)
                ->setDetails($details);
        break;
    case 2:
        $details = new Details();
        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal(9.50);
        //
        $amount = new Amount();
        $amount->setCurrency("USD")
                ->setTotal(9.50)
                ->setDetails($details);
        break;
    case 3:
        $details = new Details();
        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal(45.50);
        //
        $amount = new Amount();
        $amount->setCurrency("USD")
                ->setTotal(45.50)
                ->setDetails($details);
        break;
    case 4:
        $details = new Details();
        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal(88);
        //
        $amount = new Amount();
        $amount->setCurrency("USD")
                ->setTotal(88)
                ->setDetails($details);
        break;
}

$transaction = new Transaction();
$transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("SPO-BIT PURCHASE GOLD")
        ->setInvoiceNumber(uniqid());

$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();

//
switch ($type) {
    case 1:
        $redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true&type=1")
                ->setCancelUrl("$baseUrl/ExecutePayment.php?success=cancel");
        break;
    case 2:
        $redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true&type=2")
                ->setCancelUrl("$baseUrl/ExecutePayment.php?success=cancel");
        break;
    case 3:
        $redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true&type=3")
                ->setCancelUrl("$baseUrl/ExecutePayment.php?success=cancel");
        break;
    case 4:
        $redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true&type=4")
                ->setCancelUrl("$baseUrl/ExecutePayment.php?success=cancel");
        break;
}

$payment = new Payment();
$payment->setIntent("sale")
        ->setExperienceProfileId($pe_id)
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

// For Sample Purposes Only.
$request = clone $payment;

try {
    $payment->create($apiContext);
} catch (Exception $ex) {
//    ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
    exit(1);
}

$approvalUrl = $payment->getApprovalLink();

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);
$result = json_decode($payment, true);
//var_dump($result);
foreach ($result['links'] as $link) {
    if ($link['rel'] == 'approval_url') {
        $p_link = $link['href'];
        break;
    }
};

echo $p_link;

//return $payment;
