<?php
include_once '../inc/config.php';

$id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);
$pw = filter_input(INPUT_POST, 'pw', FILTER_DEFAULT);

$qry = "select * from members where ";
$qry .= "m_id = '$id' and ";
$qry .= "m_pw = '$pw' ";

$result = mysqli_query($conn, $qry);
$count = mysqli_num_rows($result);

if($count > 0) {
    $arr = mysqli_fetch_assoc($result);
    $_SESSION['fsport']['index'] = $arr['m_idx'];
    $_SESSION['fsport']['id'] = $id;
    echo 100;
} else {
    echo 500;
}
