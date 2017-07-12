<?php
$qry_ticket = "select * from contactus where cu_u_idx = $u_idx order by cu_idx desc";
$result_ticket = mysqli_query($conn, $qry_ticket);
if (!$result_ticket) {
    die;
}
$count_ticket = mysqli_num_rows($result_ticket);
for ($i = 0; $i < $count_ticket; $i++) {
    $arr_ticket = mysqli_fetch_assoc($result_ticket);
    $status = 'Waiting';
    if ($arr_ticket['cu_res_date'] !== null) {
        $status = $arr_ticket['cu_res_date'];
    }

    $topic = topicTicket($arr_ticket['cu_topic']);

    $tr_ticket .= <<< TT
        <tr data-index="{$arr_ticket['cu_idx']}" class="li_ticket">
            <td class="dp_font_1d4b8f" style="text-align: left; font-size: 13px; font-weight: 500; padding-left: 20px">{$arr_ticket['cu_subject']}</td>
            <td>{$topic}</td>
            <td>{$arr_ticket['cu_req_date']}</td>
            <td>$status</td>
        </tr>
TT;
}
?>
<div>
    <h3>My 1:1 Ticket</h3>
</div>
<div class="dp_lineup_table" style="text-align: center">
    <table width="100%" >
        <thead>
            <tr style="background:#42698e; color:#fff">
                <td style="width:40%; text-align: left; padding-left: 20px">Subject</td>
                <td style="width:20%">Topic</td>
                <td style="width:20%">From</td>
                <td style="width:20%">To</td>
            </tr>
        </thead>
        <tbody id="table">
            <?= $tr_ticket ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination"></ul>
    </nav>
    <button class="btn btn-primary btn_send_ticket">Send Ticket</button>
</div>