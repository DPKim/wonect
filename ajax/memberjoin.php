<?php

include_once '../inc/config.php';

$id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);
if (!filter_var($id, FILTER_VALIDATE_EMAIL)) {
    echo 501;
    die;
}

$name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
$pw = filter_input(INPUT_POST, 'pw', FILTER_DEFAULT);
$timezone = filter_input(INPUT_POST, 'timezone', FILTER_DEFAULT);
$day = filter_input(INPUT_POST, 'day', FILTER_DEFAULT);
$mon = filter_input(INPUT_POST, 'mon', FILTER_DEFAULT);
$year = filter_input(INPUT_POST, 'year', FILTER_DEFAULT);

$qry = "insert into members set ";
$qry .="m_id = '$id', ";
$qry .="m_name = '$name', ";
$qry .="m_pw = '$pw', ";
$qry .="m_timezone = $timezone, ";
$qry .="m_b_day = $day, ";
$qry .="m_b_mon = $mon, ";
$qry .="m_b_year = $year, ";
$qry .="m_enter_datetime = '$today' ";

$result = mysqli_query($conn, $qry);

if ($result) {
    
    $chk_key = encrypt($key, time().'|'.$name.'|'.$id);

    // the message
    $suject = "WELCOME TO SPO-BIT";
    $msg = <<< MAIL
            <div style="widows: 100%; border: 1px solid #aaa; position: relative">
                <table style="width:100%; border-spacing: 0px" border="0">
                    <tr style="background-image: url('http://spo-bit.com/mail/images/black_twill.png') ">
                        <td style="padding: 10px;">
                            <a href="http://spo-bit.com">
                                <img src="http://spo-bit.com/mail/images/logo.png" style="width:150px" alt="spo-bit logo">
                            </a>
                        </td>
                    </tr>
                </table>
                <div style="padding: 30px; text-align: center">
                    <h1>WELCOME TO SPO-BIT</h1>
                    <h2 style="color: #002166;">$name</h2>
                    <div>
                        Please click on the following link to confirm your new Spo-Bit account.
                    </div>
                    <div style="margin: 20px">
                        <a href="http://spo-bit.com/index.php?menu=confirm&sub=join&id={$name}&key=$chk_key">
                            <img src="http://spo-bit.com/mail/images/btn_confirm_join.png" alt="confirm email account">
                        </a>
                    </div>
                </div>
                <div style="background: #333; height: 40px; color: #fff">
                    <div style="text-align: center; padding-top: 10px">
                        © 2016 - 2017 Aman International Inc., All Rights Reserved
                    </div>
                </div>
            </div>
MAIL;

    $nameFrom = "Spo-Bit";
    $mailFrom = "support@Spo-Bit.com";
    $nameTo = $name;
    $mailTo = "$id";
    $subject = $suject;
    $content = $msg;

    $charset = "UTF-8";

    $nameFrom = "=?$charset?B?" . base64_encode($nameFrom) . "?=";
    $nameTo = "=?$charset?B?" . base64_encode($nameTo) . "?=";
    $subject = "=?$charset?B?" . base64_encode($subject) . "?=";

    $header = "Content-Type: text/html; charset=utf-8\r\n";
    $header .= "MIME-Version: 1.0\r\n";

    $header .= "Return-Path: <" . $mailFrom . ">\r\n";
    $header .= "From: " . $nameFrom . " <" . $mailFrom . ">\r\n";
    $header .= "Reply-To: <" . $mailFrom . ">\r\n";

    $send_mail = mail($mailTo, $subject, $content, $header, $mailFrom);
    if ($send_mail) {
        echo 100;
    } else {
        echo 502;
    }
} else {
    echo 500;
}