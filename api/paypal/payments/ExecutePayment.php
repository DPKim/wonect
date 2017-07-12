<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../../../inc/config.php';

$type = filter_input(INPUT_GET, 'type');

if (!isset($type)) {
    $type = 1;
}

// #Execute Payment Sample
// This is the second step required to complete
// PayPal checkout. Once user completes the payment, paypal
// redirects the browser to "redirectUrl" provided in the request.
// This sample will show you how to execute the payment
// that has been approved by
// the buyer by logging into paypal site.
// You can optionally update transaction
// information by passing in one or more transactions.
// API used: POST '/v1/payments/payment/<payment-id>/execute'.

require __DIR__ . '/../bootstrap.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

// ### Approval Status
// Determine if the user approved the payment or not
if (isset($_GET['success']) && $_GET['success'] == 'true') {

    // Get the payment Object by passing paymentId
    // payment id was previously stored in session in
    // CreatePaymentUsingPayPal.php
    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $apiContext);

    // ### Payment Execute
    // PaymentExecution object includes information necessary
    // to execute a PayPal account payment.
    // The payer_id is added to the request query parameters
    // when the user is redirected from paypal back to your site
    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    // ### Optional Changes to Amount
    // If you wish to update the amount that you wish to charge the customer,
    // based on the shipping address or any other reason, you could
    // do that by passing the transaction object with just `amount` field in it.
    // Here is the example on how we changed the shipping to $1 more than before.
    $transaction = new Transaction();
    $amount = new Amount();
    $details = new Details();

    switch ($type) {
        case 1:
            $dolar = 5;
            $deposit = 5;
            break;
        case 2:
            $dolar = 9.50;
            $deposit = 10;
            break;
        case 3:
            $dolar = 45.50;
            $deposit = 50;
            break;
        case 4:
            $dolar = 88;
            $deposit = 100;
            break;
    }
    $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($dolar);

    $amount->setCurrency('USD');
    $amount->setTotal($dolar);

    $amount->setDetails($details);
    $transaction->setAmount($amount);

    // Add the above transaction object inside our Execution object.
    $execution->addTransaction($transaction);

    try {
        // Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        $result = $payment->execute($execution, $apiContext);

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//        ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);

        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//            ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
            $purchase = 502;
            header("Location: ../../../index.php?menu=purchase&type=1&result=$purchase");
            exit(1);
        }
    } catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//        ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
        $purchase = 501;
        header("Location: ../../../index.php?menu=purchase&type=1&result=$purchase");
        exit(1);
    }

    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//    ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);
    // 회원에게 결제 금액 넣기
    $qry_up = "update members set ";
    $qry_up .= "m_deposit = ABS(m_deposit + {$deposit}) ";
    $qry_up .= "where m_idx = {$u_idx}";
    $result_up = mysqli_query($conn, $qry_up);

    if ($result_up) {
        $qry = "insert into deposit_history set ";
        $qry .= "dh_u_idx = $u_idx, ";
        $qry .= "dh_deposit = '$dolar', ";
        $qry .= "dh_amount = $deposit, ";
        $qry .= "dh_paymethod = 1, ";
        $qry .= "dh_pay_key = '{$paymentId}', ";
        $qry .= "dh_content = 'Paypal purchase', ";
        $qry .= "dh_condition = 1, ";
        $qry .= "dh_balance = ( ";
        $qry .= "select m_deposit from members ";
        $qry .= "where m_idx = $u_idx ";
        $qry .= "), ";
        $qry .= "dh_req_date = '$today', ";
        $qry .= "dh_res_date = '$today' ";
        $result = mysqli_query($conn, $qry);
        if ($result) {
            $purchase = 100;
        } else {
            $purchase = 504;
        }
    } else {
        $purchase = 503;
    }

    header("Location: ../../../index.php?menu=purchase&type=1&result=$purchase");
    die;
} else {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//    ResultPrinter::printResult("User Cancelled the Approval", null);
    $purchase = 500;
    header("Location: ../../../index.php?menu=purchase&type=1&result=$purchase");
    exit;
}