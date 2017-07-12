<?php
include_once '../inc/config.php';

$lu_idx = filter_input(INPUT_POST, 'index');
$player = filter_input(INPUT_POST, 'player', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$json_player = json_encode($player);

// 라인업 입력 (향후에는 기존 라이업을 가져왔는지 여부를 체크해서 분기 시킬 것)
$qry_line = "update lineups set ";
$qry_line .= "lu_json = '$json_player', lu_date =  '$today' ";
$qry_line .= "where lu_idx = $lu_idx";
$result_line = mysqli_query($conn, $qry_line);

if(!$result_line) {
    echo mysqli_error($conn);
    die;
} else {
    echo 100;
}

mysqli_close($conn);