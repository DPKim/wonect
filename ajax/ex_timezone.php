<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';

$qry = "select count(zone_id) as zone_count , territory, zone_id, timezone from zone ";
$qry .= "group by timezone ";
$result = mysqli_query($conn, $qry);
if ($result) {
    $count = mysqli_num_rows($result);
    for ($i = 0; $i < $count; $i++) {
        $arr = mysqli_fetch_assoc($result);
        if($arr['zone_count'] == 2){
            $qry_del = "delete from zone where zone_id = {$arr['zone_id']}";
            $result_del = mysqli_query($conn, $qry_del);
            if(!$result_del) {
                echo $i;
                die;
            }
        }
    }
}