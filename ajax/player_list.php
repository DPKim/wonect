<?php

include_once '../inc/config.php';
include_once CONTENT . 'class/class_draftgame.php';

header("Access-Control-Allow-Origin: *");
header("Content-type: text/html; charset=utf-8");
header("Pragma: no-cache");

$g_idx = filter_input(INPUT_POST, 'index');
$type = filter_input(INPUT_POST, 'type');
$lu_idx = filter_input(INPUT_POST, 'lu');

$draftgame = new Function_Draftgame($g_idx, $type, $lu_idx, $u_idx);
$value = json_encode($draftgame->make_table());

echo $value;
