<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';

$amount = filter_input(INPUT_POST, 'amount');
$payment = filter_input(INPUT_POST, 'payment');
$cate = filter_input(INPUT_POST, 'cate');

if ($cate == 'gold') {
    $qry_get_gold = "select m_deposit from members where m_idx = $u_idx";
    $result_get_gold = mysqli_query($conn, $qry_get_gold);
    
    if (!$result_get_gold) {
        echo 602;
        die;
    } else {
        $arr_get_gold = mysqli_fetch_array($result_get_gold);
//        $now_gold = bcadd($arr_get_gold[0], $amount);
        $now_gold = digitMath($arr_get_gold[0], $amount, 'plus');
    }

    $qry = "insert into deposit_history set ";
    $qry .="dh_u_idx = $u_idx, ";
    $qry .="dh_amount = $amount, ";
    $qry .="dh_paymethod = 0, ";
    $qry .="dh_pay_key = 'test_deposit', ";
    $qry .="dh_content = 'test_deposit', ";
    $qry .="dh_balance = $now_gold, ";
    $qry .="dh_condition = 1, ";
    $qry .="dh_req_date = '$today', ";
    $qry .="dh_res_date = '$today' ";

    $result = mysqli_query($conn, $qry);
    if ($result) {
        $qry = "update members set ";
        $qry .="m_deposit = $now_gold ";
        $qry .="where m_idx = $u_idx";
        $result = mysqli_query($conn, $qry);

        if ($result) {
            echo 100;
        } else {
            echo 601;
        }
    } else {
        echo 600;
    }
} else if ($cate == 'bitcoin') {

    // 현재 잔액 확인
    $qry_s = "select m_deposit from members where m_idx = $u_idx";
    $result_s = mysqli_query($conn, $qry_s);
    if ($result_s) {
        $arr_s = mysqli_fetch_array($result_s);
        $deposit = $arr_s[0];

        if ($deposit < $payment) {
            echo 400;
        } else {
            $qry = "insert into bitcoin_history set ";
            $qry .="bc_u_idx = $u_idx, ";
            $qry .="bc_amount = $amount, ";
            $qry .="bc_gold_payment = $payment, ";
            $qry .="bc_condition = 1, ";
            $qry .="bc_req_date = '$today', ";
            $qry .="bc_res_date = '$today' ";

            $result = mysqli_query($conn, $qry);
            if ($result) {
                $qry = "update bitcoin set ";
                $qry .="bc_amount = bc_amount - $amount ";
                $result = mysqli_query($conn, $qry);

                if ($result) {
//                    $now_gold = bcsub($deposit, $payment);
                    $now_gold = digitMath($deposit, $payment, 'minus');

                    $qry = "insert into deposit_history set ";
                    $qry .="dh_u_idx = $u_idx, ";
                    $qry .="dh_amount = -$payment, ";
                    $qry .="dh_paymethod = 0, ";
                    $qry .="dh_pay_key = 'Bit-coin', ";
                    $qry .="dh_content = 'Bit-coin', ";
                    $qry .="dh_balance = '$now_gold', ";
                    $qry .="dh_condition = 1, ";
                    $qry .="dh_req_date = '$today', ";
                    $qry .="dh_res_date = '$today' ";

                    $result = mysqli_query($conn, $qry);
                    
                    if ($result) {
                        $qry = "update members set ";
                        $qry .="m_deposit = $now_gold ";
                        $qry .="where m_idx = $u_idx";
                        
                        $result = mysqli_query($conn, $qry);

                        if ($result) {
                            echo 100;
                        } else {
                            echo 505;
                        }
                    } else {
                        echo 503;
                    }
                } else {
                    echo 502;
                }
            } else {
                echo 500;
            }
        }
    } else {
        echo 501;
    }
}