<?php

include_once '../inc/config.php';

$id = filter_input(INPUT_POST, 'index');
$qry_ticket = "select * from contactus where cu_u_idx = $u_idx and cu_idx = $id";
$result_ticket = mysqli_query($conn, $qry_ticket);
if (!$result_ticket) {
    die;
}
$arr_ticket = mysqli_fetch_assoc($result_ticket);
$message = nl2br($arr_ticket['cu_message']);
$tr_ticket .= <<< TT
        <tr class="ticket_detail" style="background:#fff; border-bottom: 1px solid #ddd;">
            <td colspan='4' style="padding:10px">
                <table class="table_ticket_detail">
                    <tr>
                        <td>내용</td>
                        <td>$message</td>
                    </tr>
                    <tr>
                        <td>답변내용</td>
                        <td></td>
                    </tr>
                </table>
                <div>
                    <button class="dp_float_right dp_margin_top10 btn btn-default btn-sm btn_ticket_detail_close">Close</button>
                </div>
            </td>
        </tr>
TT;

echo $tr_ticket;
