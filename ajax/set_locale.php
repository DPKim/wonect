<?php

include_once '../inc/config.php';

$language = filter_input(INPUT_POST, 'language');
$timezone = filter_input(INPUT_POST, 'timezone');

$_SESSION['LOCALE']['language'] = $language;
$_SESSION['LOCALE']['locale'] = $timezone;

if (isset($_SESSION['LOCALE'])) {
    if ($_SESSION['LOCALE']['locale'] === $timezone) {
        echo 100;
        die;
    }
}
echo 500;
