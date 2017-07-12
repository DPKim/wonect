<?php

include_once '../inc/config.php';

$post['topic'] = filter_input(INPUT_POST, 'topic');
$post['mail'] = filter_input(INPUT_POST, 'mail');
if (get_magic_quotes_gpc() == false) {
    $post['subject'] = addslashes(filter_input(INPUT_POST, 'subject'));
    $post['message'] = addslashes(filter_input(INPUT_POST, 'message'));
} else {
    $post['subject'] = filter_input(INPUT_POST, 'subject');
    $post['message'] = filter_input(INPUT_POST, 'message');
}
$post['key'] = filter_input(INPUT_POST, 'key');

$ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');

$secretKey = '6Ld-UBsUAAAAAO8UeMGO7FZYwZc036v7uhvbY8tA';
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $post['key']);
$responseKeys = json_decode($response, true);

if ($responseKeys['success'] !== true) {
    echo 500;
} else {
    $qry = "insert into contactus set ";
    $qry .= "cu_topic = {$post['topic']}, ";
    if ($u_idx) {
        $qry .= "cu_u_idx = $u_idx, ";
    }
    $qry .= "cu_mail = '{$post['mail']}', ";
    $qry .= "cu_subject = '{$post['subject']}', ";
    $qry .= "cu_message = '{$post['message']}', ";
    $qry .= "cu_req_date = '$today' ";
    $result = mysqli_query($conn, $qry);

    if (!$result) {
//        echo mysqli_error($conn);
       echo 501;
    } else {
        echo 100;
    }
}