<?php

$id = filter_input(INPUT_POST, 'paymentId');
//$id = 'PAY-3MS36649PE095382DLEFL44Q';

/** @var Payment $createdPayment */
$createdPayment = require 'CreatePayment.php';

use PayPal\Api\Payment;

$paymentId = $createdPayment->getId();
//
//$paymentId = $id;

// ### Retrieve payment
// Retrieve the payment object by calling the
// static `get` method
// on the Payment class by passing a valid
// Payment ID
// (See bootstrap.php for more on `ApiContext`)
try {
    $payment = Payment::get($paymentId, $apiContext);
    $json = json_decode($payment, true);
//    var_dump($json);
    $status = $json['payer']['status'];
    $invoice = $json['transactions'][0]['invoice_number'];

    $res['status'] = $status;
    $res['invoice'] = $invoice;
    $res_json = json_encode($res);

    echo $res_json; 
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//    ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);

//return $payment;
