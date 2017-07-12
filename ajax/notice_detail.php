<?php

include_once '../inc/config.php';

$id = filter_input(INPUT_POST, 'index');
$qry_ticket = "select * from notice where nt_idx = $id";
$result_ticket = mysqli_query($conn, $qry_ticket);
if (!$result_ticket) {
    die;
}
$arr_ticket = mysqli_fetch_assoc($result_ticket);
$message = nl2br($arr_ticket['nt_content']);
$tr_ticket .= <<< TT
        <tr class='notice_detail' style="background:#fff;">
            <td colspan='2' style="padding:10px; text-align:left">
                <div>$message</div>
                <div>
                    <button class="dp_float_right dp_margin_top10 btn btn-default btn-sm btn_notice_detail_close">Close</button>
                </div>
            </td>
        </tr>
TT;

echo $tr_ticket;
