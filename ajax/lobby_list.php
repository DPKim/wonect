<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';
include_once CONTENT . 'class/class_lobby.php';

$category = filter_input(INPUT_POST, 'category');
//
$date = filter_input(INPUT_POST, 'date');
$sub_menu = filter_input(INPUT_POST, 'sub_menu');
$search = filter_input(INPUT_POST, 'search');

$var['date'] = $date;
$var['locale'] = $locale;
$var['sub_menu'] = $sub_menu;
$var['search'] = $search;

$lobby = new LobbyContest($category);
$list = $lobby->proccess($var);

if ($list) {
    echo $list;
} else {
    echo '<div class="alert alert-info" role="alert" style="margin-bottom:0px; text-align:center">There are no existing contests.</div>';
}
