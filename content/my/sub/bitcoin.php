<?php
$qry_bc = "select * from bitcoin_history where bc_u_idx = $u_idx order by bc_idx desc ";
$result_bc = mysqli_query($conn, $qry_bc);
if ($result_bc) {
    $count_bc = mysqli_num_rows($result_bc);
    for ($i = 0; $i < $count_bc; $i++) {
        $arr = mysqli_fetch_assoc($result_bc);

        $qr_amount = numberFormat_for_float($arr['bc_amount']);
        switch ($arr['bc_pay_type']) {
            case 0:
                $qr_type = 'TEST';
                break;
        }

        switch ($arr['bc_condition']) {
            case 0:
                $condition = 'Waiting';
                break;
            case 1:
                $condition = 'Completed';
                break;
            case 2:
                $condition = 'Canceled';
                break;
        }
        $tr_bit .=<<< TR
                <tr style="background:#fff; color:#333; display:none; border-bottom: 1px solid #ddd">
                    <td>{$arr['bc_req_date']}</td>
                    <td>{$arr['bc_pay_address']}</td>
                    <td>{$arr['bc_tid']}</td>
                    <td>{$qr_amount} BTC</td>
                    <td>{$arr['bc_gold_payment']} Gold</td>
                    <td>{$arr['bc_rate']} BTC</td>
                    <td>{$condition}</td>
                </tr>
TR;
    }
}
?>
<div>
    <h3>BIT-COIN HISTORY</h3>
</div>
<div class="dp_lineup_table" style="text-align: center">
    <table width="100%">
        <thead>
            <tr style="background:#42698e; color:#fff">
                <td style="width:15%">Date</td>
                <td style="width:20%">Address</td>
                <td style="width:15%">TID</td>
                <td style="width:20%">Purchase BTC</td>
                <td style="width:10%">Gold</td>
                <td style="width:10%">USD/BTC</td>
                <td style="width:10%">Status</td>
            </tr>
        </thead>
        <tbody id="table">
            <?= $tr_bit ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination"></ul>
    </nav>
</div>