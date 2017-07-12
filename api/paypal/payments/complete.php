<?php

include_once '../../../inc/config.php';

$result['success'] = filter_input(INPUT_GET, 'success');
$result['type'] = filter_input(INPUT_GET, 'type');
$result['paymentId'] = filter_input(INPUT_GET, 'paymentId');

if ($result['success'] == 'true') {
    switch ($result['type']) {
        case '1':
            $deposit = 5;
            $amount = 5;
            break;
        case '2':
            $deposit = 9.50;
            $amount = 10;
            break;
        case '3':
            $deposit = 45.50;
            $amount = 50;
            break;
        case '4':
            $deposit = 88.00;
            $amount = 100;
            break;
    }

    // 회원에게 결제 금액 넣기
    $qry_up = "update members set ";
    $qry_up .= "m_deposit = ABS(m_deposit + {$amount}) ";
    $qry_up .= "where m_idx = {$u_idx}";
    $result_up = mysqli_query($conn, $qry_up);

    if ($result_up) {
        $qry = "insert into deposit_history set ";
        $qry .= "dh_u_idx = $u_idx, ";
        $qry .= "dh_deposit = '$deposit', ";
        $qry .= "dh_amount = $amount, ";
        $qry .= "dh_paymethod = 1, ";
        $qry .= "dh_pay_key = '{$result['paymentId']}', ";
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
    $purchase = $result['success'];
}

mysqli_close($conn);
header("Location: ../../../index.php?menu=purchase&type=1&result=$purchase");
