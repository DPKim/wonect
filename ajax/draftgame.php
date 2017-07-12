<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';

$idx = filter_input(INPUT_POST, 'id');
$coin = filter_input(INPUT_POST, 'coin');
$category = filter_input(INPUT_POST, 'category');
$game = filter_input(INPUT_POST, 'game');
$player = filter_input(INPUT_POST, 'player', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$count = count($player);

// 현재 엔트리에 이상 없는지 체크할 것
$qry_chk_entry = "select g_size, g_entry from game ";
$qry_chk_entry .="where g_idx = $game ";
$result_chk_entry = mysqli_query($conn, $qry_chk_entry);
$arr_chk_entry = mysqli_fetch_array($result_chk_entry);

$count_size = $arr_chk_entry[0];
$count_entry = $arr_chk_entry[1];

if ($count_entry >= $count_size) {
    echo 507;
    die;
}

// 라인업 입력 (향후에는 기존 라이업을 가져왔는지 여부를 체크해서 분기 시킬 것)
$qry_line = "insert into lineups ";
$qry_line .= "(lu_u_idx, lu_gc_idx, lu_g_idx) ";
$qry_line .= "values ";
$qry_line .= "($u_idx, $category, $game) ";
$result_line = mysqli_query($conn, $qry_line);

if (!$result_line) {
    echo 501;
    die;
}

$saveIdx = mysqli_insert_id($conn);

// 라인업 히스토리에 선수 데이터 넣기
for ($i = 0; $i < $count; $i++) {
    $arr = get_player_info($player[$i]['player_id'], $category);
    //
    $pos = chg_pos($category, $arr['player_primary_position']);
    //
    switch ($category) {
        case 2:
            $name = $arr['player_first_name'] . ' ' . $arr['player_last_name'];
            break;
        case 3:
            $name = $arr['player_nickname'];
            break;
    }
    //    
    $qry_lh = "insert into lineups_history set ";
    $qry_lh .= "lu_idx = $saveIdx, ";
    $qry_lh .= "game_id = '{$player[$i]['game_id']}', ";
    $qry_lh .= "player_id = '{$arr['player_id']}', ";
    $qry_lh .= 'player_name = "' . $name . '", ';
    $qry_lh .= "player_pos = '{$pos}', ";
    $qry_lh .= "player_team_id = '{$arr['team_id']}', ";
    $qry_lh .= "player_salary = {$arr['player_salary']}, ";
    $qry_lh .= "reg_date = now(), ";
    $qry_lh .= "reg_update = now() ";
    $result_lh = mysqli_query($conn, $qry_lh);
    if (!$result_lh) {
//        echo $qry_lh;
        die;
    }
}

function get_player_info($player_id, $cate) {
    $db_conn = new DB_conn;
    $conn = $db_conn->dbconnect_game();
    //
    $cate_name = cage_name($cate);
    //
    $qry = "select * from {$cate_name}_team_profile_player ";
    $qry .= "where idx = $player_id ";
    $result = mysqli_query($conn, $qry);
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

$qry_join = "insert into join_contest ";
$qry_join .= "(jc_u_idx, jc_game, jc_lineups, jc_date) ";
$qry_join .= "values ";
$qry_join .= "($u_idx, $game, $saveIdx, '$today') ";
$result_join = mysqli_query($conn, $qry_join);

if (!$result_join) {
    echo 502;
    die;
}

//회원 머니 차감
$qry_get_gold = "select m_deposit from members where m_idx = $u_idx";
$result_get_gold = mysqli_query($conn, $qry_get_gold);

if (!$result_get_gold) {
    echo 503;
    die;
} else {
    $arr_get_gold = mysqli_fetch_array($result_get_gold);
//    $now_gold = bcsub($arr_get_gold[0], $coin);
    $now_gold = digitMath($arr_get_gold[0], $coin, 'minus');
}

$qry = "update members set ";
$qry .="m_deposit = $now_gold ";
$qry .="where m_idx= $u_idx ";
$result = mysqli_query($conn, $qry);

if (!$result) {
    echo 504;
    die;
}

// 로그에 쌓음
$qry_log = "insert into deposit_history set ";
$qry_log .="dh_u_idx = $u_idx, ";
$qry_log .="dh_amount = -$coin, ";
$qry_log .="dh_paymethod = 0, ";
$qry_log .="dh_pay_key = 'join_contest', ";
$qry_log .="dh_content = 'Join the contest', ";
$qry_log .="dh_balance = $now_gold, ";
$qry_log .="dh_condition = 1, ";
$qry_log .="dh_req_date = '$today', ";
$qry_log .="dh_res_date = '$today' ";

$result_log = mysqli_query($conn, $qry_log);
if (!$result_log) {
    echo 505;
}

//게임 참여 카운트 추가
$qry_game = "update game set ";
$qry_game .="g_entry = g_entry + 1 ";
$qry_game .="where g_idx= $game ";
$result_game = mysqli_query($conn, $qry_game);

if ($result_game) {
    echo 100;
} else {
    echo 506;
    die;
}

mysqli_close($conn);
