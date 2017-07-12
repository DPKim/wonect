<?php

include_once '../inc/config.php';

$id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);
$email = filter_input(INPUT_POST, 'mail', FILTER_DEFAULT);
$name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
$gender = filter_input(INPUT_POST, 'gender', FILTER_DEFAULT);
$locale = filter_input(INPUT_POST, 'locale', FILTER_DEFAULT);
$timezone = filter_input(INPUT_POST, 'timezone', FILTER_DEFAULT);

$qry_chk = "select * from members where ";
$qry_chk .= "m_id = '$email' ";
$result_chk = mysqli_query($conn, $qry_chk);
//
if ($result_chk) {
    $chk_exist = mysqli_num_rows($result_chk);
    if ($chk_exist > 0) {
        $arr_chk = mysqli_fetch_assoc($result_chk);
        //
        if ($arr_chk['m_key_id'] !== $id) {
            // 기존에 등록된 이메일 있음
            echo 600;
            die;
        } else {
            // 기존에 등록된 회원이면 로그인 시킨다.          
            $_SESSION['fsport']['index'] = $arr_chk['m_idx'];
            $_SESSION['fsport']['id'] = $arr_chk['m_id'];
            if (isset($_SESSION['fsport']['index'])) {
                echo 100;
                die;
            } else {
                echo 700;
                die;
            }
        }
    }
} else {
    $res['msg'] = 'DB error';
    $res['code'] = 502;
    die;
}

//국가 코드
if ($locale) {
    $country = substr($locale, -2, 2);
} else {
    $country = 'UTC';
}

//겹치지 않는다면 회원을 만든다.
$qry = "insert into members set ";
$qry .="m_id = '$email', ";
$qry .="m_name = '$name', ";
$qry .="m_pw = '', ";
$qry .="m_timezone = (select zone_id from zone where territory = '$country'), ";
$qry .="m_b_day = 01, ";
$qry .="m_b_mon = 01, ";
$qry .="m_b_year = 1900, ";
$qry .="m_key_id = '$id', ";
$qry .="m_type = 2, ";
$qry .="m_enter_datetime = '$today' ";

$result = mysqli_query($conn, $qry);

if ($result) {
    $arr = mysqli_fetch_assoc($result);
    //
    $_SESSION['fsport']['index'] = mysqli_insert_id($conn);
    $_SESSION['fsport']['id'] = $email;
    if (isset($_SESSION['fsport']['index'])) {
        echo 101;
        die;
    } else {
        echo 701;
        die;
    }
} else {
    echo 500;
    die;
}