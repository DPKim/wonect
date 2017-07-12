<?php

include_once '../inc/config.php';

$post['mail'] = filter_input(INPUT_POST, 'mail');
$post['key'] = filter_input(INPUT_POST, 'key');

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $post['key']);
$responseKeys = json_decode($response, true);

$res_result = array(
    'error' => '',
    'result' => 500
);

if ($responseKeys['success'] !== true) {
    $res_result['error'] = 'Key error';
} else {
    $qry = "select * from members ";
    $qry .= "where m_id = '{$post['mail']}' ";
    $result = mysqli_query($conn, $qry);

    if (!$result) {
        $res_result['error'] = 'DB connect error';
    } else {
        $arr = mysqli_fetch_assoc($result);
        
        if (!$arr['m_idx']) {
            $res_result['error'] = 'No email infomation';
            $res_result['result'] = 501;
            echo json_encode($res_result);
            die;
        }

        $rand_text = getCoupon(8);

        $chk_key = encrypt($key, time() . '|' . $rand_text . '|' . $name . '|' . $id);

        // the message
        $suject = "Spo-Bit.com Password Assistance";
        $id = $arr['m_id'];

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
                <div style="padding: 30px; text-align: left">
                    <h1>$id</h1>
                    <div>
                        We received a request to reset the password associated with this email address. <br>
                        Check and use below your temporary password.<br>
                        <span style="color: #ff0101">Don not forget change new password when you sign in.</span>
                    </div>
                    <div style="margin: 20px 0px 20px 0px; background-color: #1d4b8f; border-radius: 14px; padding: 10px; width: 300px; color:#fff; text-align: center">
                        $rand_text
                    </div>
                    <div>
                        If you believe this email was sent in error, or if you have any problems with the reset, please email support@Spo-Bit.com for further assistance.
                        <br><br>
                        Thank you,<br>
                        Spo-Bit Customer Support
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
        $nameTo = $id;
        $mailTo = $id;
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
            $qry_up = "update members set ";
            $qry_up .= "m_pw ='$rand_text' ";
            $qry_up .= "where m_idx = {$arr['m_idx']} ";
            $result_up = mysqli_query($conn, $qry_up);

            if ($result_up) {
                $res_result['error'] = 'Complete';
                $res_result['result'] = 100;
            } else {
                $res_result['error'] = 'DB update error';
            }
        } else {
            $res_result['error'] = 'Send mail error';
        }
    }
}

echo json_encode($res_result);
