<?php
$qry_gd = "select * from deposit_history where dh_u_idx = $u_idx order by dh_idx desc ";
$result_gd = mysqli_query($conn, $qry_gd);
if ($result_gd) {
    $count_gd = mysqli_num_rows($result_gd);
    for ($i = 0; $i < $count_gd; $i++) {
        $arr = mysqli_fetch_assoc($result_gd);

        $qr_amount = numberFormat_for_float($arr['dh_amount']);
        $qr_balance = numberFormat_for_float($arr['dh_balance']);
        switch ($arr['dh_paymethod']) {
            case 0:
                $gold_method = 'SPO-BIT';
                break;
            case 1:
                $gold_method = 'Paypal';
                break;
            case 2:
                $gold_method = 'BTC';
                break;
        }

        if ($arr['dh_deposit']) {
            $deposit = '$' . $arr['dh_deposit'];
        } else {
            $deposit = '-';
        }

        $tr_gold .=<<< TR
                <tr style="background:#fff; color:#333; display:none; border-bottom: 1px solid #ddd">
                    <td>{$arr['dh_req_date']}</td>
                    <td>{$arr['dh_content']}</td>
                    <td>{$gold_method}</td>
                    <td>{$deposit}</td>
                    <td>{$qr_amount} GOLD</td>
                    <td>{$qr_balance} GOLD</td>
                </tr>
TR;
    }
}
?>
<div>
    <h3>GOLD HISTORY</h3>
</div>
<div class="dp_lineup_table" style="text-align: center">
    <table width="100%" >
        <thead>
            <tr style="background:#42698e; color:#fff">
                <td style="width:15%">Date</td>
                <td style="width:20%">Contents</td>
                <td style="width:15%">PG</td>
                <td style="width:15%">Amount</td>
                <td style="width:15%">Gold</td>
                <td style="width:20%">Balance</td>
            </tr>
        </thead>
        <tbody id="table">
            <?= $tr_gold ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination"></ul>
    </nav>
</div>