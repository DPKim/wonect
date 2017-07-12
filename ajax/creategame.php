<?php

include_once '../inc/config.php';

$day = filter_input(INPUT_POST, 'day');
$detail = filter_input(INPUT_POST, 'detail', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$fee = filter_input(INPUT_POST, 'fee');
$game = filter_input(INPUT_POST, 'game');
$hour = filter_input(INPUT_POST, 'hour');
$min = filter_input(INPUT_POST, 'min');
$mon = filter_input(INPUT_POST, 'mon');
$name = str_replace("*w*w", "\'", filter_input(INPUT_POST, 'name'));
$prize = filter_input(INPUT_POST, 'prize');
$size = filter_input(INPUT_POST, 'size');
$multi = filter_input(INPUT_POST, 'multi');
$type = filter_input(INPUT_POST, 'type');
$type2 = filter_input(INPUT_POST, 'type2');
$week = filter_input(INPUT_POST, 'week');
$year = filter_input(INPUT_POST, 'year');
$timezone = filter_input(INPUT_POST, 'timezone');

// 시간 재정비
$new_time = $year . '-' . $mon . '-' . $day . ' ' . $hour . ':' . $min;
$date = new DateTime($new_time);
$datetime = $date->format('Y-m-d H:i:s');

// 게임 정보 json
$data_json = json_encode($detail);

//DB 입력
$qry = "insert into game set ";
$qry .="g_sport = '$game', ";
$qry .="g_size = $size, ";
$qry .="g_multi_max = $multi, ";
$qry .="g_fee = $fee, ";
$qry .="g_prize = $prize, ";
$qry .="g_type = '$type', ";
$qry .="g_type2 = '$type2', ";
$qry .="g_name = '$name', ";
$qry .="g_u_idx = $u_idx, ";
$qry .="g_date = '$datetime', ";
$qry .="g_timezone = '$timezone', ";
$qry .="g_json = '$data_json', ";
$qry .="g_c_date = '$today' ";
$result = mysqli_query($conn, $qry);

if ($result) {
    echo 100;
} else {
    echo mysqli_error($conn);
}