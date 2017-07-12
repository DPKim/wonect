<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';
include_once CONTENT . 'class/class_rank_reward.php';


$entry_size = 2; 
$entry_fee =2; 
$reward_limit = 0;

$test = new RankReward ($entry_size, $entry_fee, $reward_limit);
foreach ($test -> make_rank_arr() as $value) {
    echo $value['rank'];
    echo ' = ';
    echo $value['reward'].'G';
    echo '<br>------------------------------------<br>';
}