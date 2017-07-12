<?php

include_once '../inc/config.php';

$qry = "select bc_amount from bitcoin";
$result = mysqli_query($conn, $qry);

if ($result) {
    $arr = mysqli_fetch_array($result);
    echo $arr[0];
} else {
    echo 500;
}
