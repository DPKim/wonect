<?php

include_once '../inc/config.php';
include_once CONTENT . 'class/class_chk_bitcoin.php';

$code = filter_input(INPUT_POST, 'code');

$bitcoin = new ChekcBitcoin;
$chk_bitcoin = $bitcoin->isValid($code);

if($chk_bitcoin){
    
    //중복 체크
    $qry_chk = "select m_bitcoin_code from members ";
    $qry_chk .= "where m_bitcoin_code = '$code' ";
    $result_chk = mysqli_query($conn, $qry_chk);
    if(!$result_chk) {
        echo 500;
    }
    
    if(mysqli_num_rows($result_chk) > 0) {
        echo 400;
        die;
    }
    
    $qry = "update members set ";
    $qry .= "m_bitcoin_code = '$code' ";
    $qry .= "where m_idx = $u_idx ";
    $result = mysqli_query($conn, $qry);
    if($result) {
        echo 100;
    } else {
        echo 500;
    }
} else {
    echo 501;
}