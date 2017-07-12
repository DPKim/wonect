<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';

$index = filter_input(INPUT_POST, 'index');
$address = filter_input(INPUT_POST, 'address');
$amount = filter_input(INPUT_POST, 'amount');
$bit = filter_input(INPUT_POST, 'bit');
$rate = filter_input(INPUT_POST, 'rate');

// 회원 정보와 주소값 대조하기
$qry_chk = "select m_bitcoin_code, bc_amount, m_deposit  ";
$qry_chk .= "from members ";
$qry_chk .= "left join bitcoin on bc_idx = 1 ";
$qry_chk .= "where m_idx = $u_idx ";
$result_chk = mysqli_query($conn, $qry_chk);
$arr_chk = mysqli_fetch_array($result_chk);

if ($address == $arr_chk[0]) {

    // 내가 보유하고 있는 금액 한도에 맞는가
    $diff_my = digitMath($arr_chk[2], $amount, $type = 'minus');
    if ($diff_my >= 0) {
        $qry_up = "update members set ";
        $qry_up .="m_deposit = '$diff_my' ";
        $qry_up .="where m_idx = $u_idx ";
        $result_up = mysqli_query($conn, $qry_up);
        if (!$result_up) {
            echo 504;
            die;
        }
    } else {
        echo 503;
        die;
    }

    // 현재 남아 있는 비트 코인과 계산하고 처리하기
    $diff = (($arr_chk[1] * 100000000) - ($bit * 100000000)) / 100000000;
    if ($diff >= 0) {
        $qry_up = "update bitcoin set ";
        $qry_up .="bc_amount = '$diff' ";
        $result_up = mysqli_query($conn, $qry_up);
        if (!$result_up) {
            echo 502;
            die;
        }
    } else {
        echo 501;
        die;
    }

    $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
    $rand = rand(0, 9);
    $tid = 'BC-' . $alpha[$rand] . '-' . time() * rand(6, 8);

    $qry = "insert into bitcoin_history set ";
    $qry .= "bc_u_idx = $u_idx, ";
    $qry .= "bc_tid = '$tid', ";
    $qry .= "bc_amount = '$bit', ";
    $qry .= "bc_pay_type = 1, ";
    $qry .= "bc_pay_address = '$address', ";
    $qry .= "bc_gold_payment = '$amount', ";
    $qry .= "bc_rate = '$rate', ";
    $qry .= "bc_req_date = '$today' ";
    $result = mysqli_query($conn, $qry);

    if ($result) {
        $qry = "insert into deposit_history set ";
        $qry .= "dh_u_idx = $u_idx, ";
        $qry .= "dh_amount = '-$amount', ";
        $qry .= "dh_paymethod = 2, ";
        $qry .= "dh_pay_key = 'Bit-Coin Purchase', ";
        $qry .= "dh_content = 'Bit-Coin Purchase', ";
        $qry .= "dh_balance = '$diff_my', ";
        $qry .= "dh_req_date = '$today' ";
        $result = mysqli_query($conn, $qry);
        
        if($result) {
            echo 100;
        } else {
            echo 505;
        }
    } else {
        echo 500;
    }
} else {
    echo 600;
}