<?php

include_once '../inc/config.php';

$new_pw = filter_input(INPUT_POST, 'new_pw');
$cf_new_pw = filter_input(INPUT_POST, 'cf_new_pw');

if ($new_pw == $cf_new_pw) {
    $qry = "update members set m_pw = '$new_pw' ";
    $qry .= "where m_idx = $u_idx";
    $result = mysqli_query($conn, $qry);
    
    if($result) {
        echo 100;
    } else {
        echo $qry;
    }
} else {
    echo 501;
}