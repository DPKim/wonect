<?php

include_once '../../../inc/config.php';
$createdPayment = require 'CreatePayment.php';

use PayPal\Api\Payment;

$result['success'] = filter_input(INPUT_GET, 'success');
$result['type'] = filter_input(INPUT_GET, 'type');
$result['paymentId'] = filter_input(INPUT_GET, 'paymentId');

if ($result['success'] == 'true') {

    $paymentId = $result['paymentId'];

    try {
        $payment = Payment::get($paymentId, $apiContext);
        $json = json_decode($payment, true);

        $status = $json['payer']['status'];
        $invoice = $json['transactions'][0]['invoice_number'];

        $res['status'] = $status;
        $res['invoice'] = $invoice;
    } catch (Exception $ex) {
        $purchase = 504;
    }
    //
    if ($res['invoice']) {
        switch ($result['type']) {
            case '1':
                $amount = 5;
                break;
            case '2':
                $amount = 10;
                break;
            case '3':
                $amount = 50;
                break;
            case '4':
                $amount = 100;
                break;
        }

        $key = $result['paymentId'] . '|' . $res['invoice'];

        // 기존에 값이 들어가 있으면 더 이상 넣지 않음
        $qry_chk = "select * from deposit_history ";
        $qry_chk .= "where dh_pay_key = '$key'  ";
        $result_chk = mysqli_query($conn, $qry_chk);
        $count_chk = mysqli_num_rows($result_chk);

        if ($count_chk == 0) {
            // 회원에게 결제 금액 넣기
            $qry_up = "update members set ";
            $qry_up .= "m_deposit = ABS(m_deposit + {$amount}) ";
            $qry_up .= "where m_idx = {$u_idx}";
            $result_up = mysqli_query($conn, $qry_up);

            if ($result_up) {
                $qry = "insert into deposit_history set ";
                $qry .= "dh_u_idx = $u_idx, ";
                $qry .= "dh_amount = $amount, ";
                $qry .= "dh_paymethod = 1, ";
                $qry .= "dh_pay_key = '{$key}', ";
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
                    $purchase = 502;
                }
            } else {
                $purchase = 501;
            }
        } else {
            $purchase = 505;
        }
    } else {
        $purchase = 503;
    }
} else {
    $purchase = $result['success'];
}

mysqli_close($conn);
header("Location: ../../../index.php?menu=purchase&type=1&result=$purchase");
