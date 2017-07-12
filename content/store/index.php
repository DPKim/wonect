<?php
if ($sub == 'history_gd') {
    $qry = "select * from deposit_history where dh_u_idx = $u_idx order by dh_idx desc ";
    $result = mysqli_query($conn, $qry);
    if ($result) {
        $count = mysqli_num_rows($result);
        for ($i = 0; $i < $count; $i++) {
            $arr = mysqli_fetch_assoc($result);

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
            
            if($arr['dh_deposit']) {
                $deposit = '$'.$arr['dh_deposit'];
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
    } else {
        die;
    }
} else if ($sub == 'history_bc') {
    $qry = "select * from bitcoin_history where bc_u_idx = $u_idx order by bc_idx desc ";
    $result = mysqli_query($conn, $qry);
    if ($result) {
        $count = mysqli_num_rows($result);
        for ($i = 0; $i < $count; $i++) {
            $arr = mysqli_fetch_assoc($result);

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
    } else {
        die;
    }
}

$qry = "select m_deposit, m_bitcoin_code from members where m_idx = $u_idx";
$result = mysqli_query($conn, $qry);
$arr = mysqli_fetch_array($result);

if ($arr[1]) {
    $my_bitcode = $arr[1];
    $my_bitcode .= '<div><button class="btn btn-default btn_edit_code">EDIT CODE</button></div>';
} else {
    $my_bitcode = 'Please input your Bit-coin address';
    $my_bitcode .= '<div><button class="btn btn-default">INPUT CODE</button></div>';
}

$qry_bc = "select bitcoin.bc_amount, bitcoin_price.price, bitcoin_price.markets_market from bitcoin ";
$qry_bc .= "left join bitcoin_price on bitcoin_price.idx = 1";
$result_bc = mysqli_query($conn, $qry_bc);

if ($result_bc) {
    $arr_bc = mysqli_fetch_array($result_bc);
    // 
    $bitcoin_price = $arr_bc[1];
    $bitcoin_market = $arr_bc[2];

    $bit_30 = bitcoinNow(30, $bitcoin_price);
    $bit_50 = bitcoinNow(50, $bitcoin_price);
    $bit_100 = bitcoinNow(100, $bitcoin_price);
    $bit_500 = bitcoinNow(500, $bitcoin_price);
} else {
    die;
}

// 비트코인 현재 시세에 맞게
function bitcoinNow($amount, $bitcoin_price) {
    $cal = substr((1 / $bitcoin_price) * 0.9 * $amount, 0, 10);
    return $cal;
}
?>
<div id="back_box" class="dp_cotent_box">
    <div style="width: 1140px; margin: 0 auto">
        <div class="col-md-2">
            <div class="create_title">
                <span class="dp_title">STORE</span>
            </div>
            <div class="panel panel-default">               
                <div class="panel-heading">My Balance</div>
                <div class="panel-body" style="border-bottom: 1px solid #efefef">
                    <p style="font-weight: bold">GOLD</p>
                    <span> $<?= numberFormat_for_float($arr[0]) ?></span>
                </div>
            </div>
            <div class="panel panel-default">               
                <div class="panel-heading">History</div>
                <div class="list-group">
                    <a href="index.php?menu=store&sub=history_gd" class="list-group-item">Gold</a>                   
                    <a href="index.php?menu=store&sub=history_bc" class="list-group-item">Bit-Coin</a>
                </div>
            </div>
        </div>
        <div class="col-md-10 dp_store_body">
            <div class="dp_store_content ">
                <?php
                if ($sub == null) {
                    ?>    
                    <img src="<?= INC_PUBLIC ?>images/banner/banner_store.png">
                    <div>
                        <img src="<?= INC_PUBLIC ?>images/tt_gold_purchase.png">
                    </div>
                    <ul id="gold_purchase">
                        <li data-index="1">
                            <img src="<?= INC_PUBLIC ?>images/gold_5.png">
                        </li>
                        <li data-index="2">
                            <img src="<?= INC_PUBLIC ?>images/gold_10.png">
                        </li>
                        <li data-index="3">
                            <img src="<?= INC_PUBLIC ?>images/gold_30.png">
                        </li>
                        <li data-index="4">
                            <img src="<?= INC_PUBLIC ?>images/gold_50.png">
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="bitcoin_box">
                        <div class="next_bitcoin_count">
                            NEXT VOLUME : 
                            <span>09:34:24</span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="bitcoin_limit">
                            Purchasable amount for today : <span id="bitcoin_limit"><?= numberFormat_for_float($arr_bc[0]) ?> BTC</span>
                        </div>
                        <div>
                            <ul id="bitcoin_purchase">
                                <li data-index="1" data-bit-amount="<?= $bit_30 ?>">
                                    <div class="bitcoin_amount">
                                        <i class="fa fa-btc" aria-hidden="true"></i>
                                        <?= $bit_30 ?>
                                    </div>
                                    <div>
                                        <img src="<?= INC_PUBLIC ?>images/bitcoin_0005.png">
                                    </div>
                                </li>
                                <li data-index="2" data-bit-amount="<?= $bit_50 ?>">
                                    <div class="bitcoin_amount">
                                        <i class="fa fa-btc" aria-hidden="true"></i>
                                        <?= $bit_50 ?>
                                    </div>
                                    <div>
                                        <img src="<?= INC_PUBLIC ?>images/bitcoin_001.png">
                                    </div>

                                </li>
                                <li data-index="3" data-bit-amount="<?= $bit_100 ?>">
                                    <div class="bitcoin_amount">
                                        <i class="fa fa-btc" aria-hidden="true"></i>
                                        <?= $bit_100 ?>
                                    </div>
                                    <div>
                                        <img src="<?= INC_PUBLIC ?>images/bitcoin_003.png">
                                    </div>
                                </li>
                                <li data-index="4" data-bit-amount="<?= $bit_500 ?>">
                                    <div class="bitcoin_amount">
                                        <i class="fa fa-btc" aria-hidden="true"></i>
                                        <?= $bit_500 ?>
                                    </div>
                                    <div>
                                        <img src="<?= INC_PUBLIC ?>images/bitcoin_005.png">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
                } else if ($sub == 'history_gd') {
                    ?>
                    <div>
                        <span class="dp_title_222">GOLD HISTORY</span>
                    </div>
                    <div class="dp_lineup_table" style="padding: 20px;text-align: center">
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
                    <?php
                } else if ($sub == 'history_bc') {
                    ?>
                    <div>
                        <span class="dp_title_222">Bit-Coin HISTORY</span>
                    </div>
                    <div class="dp_lineup_table" style="padding: 20px;text-align: center">
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
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div id="cal_content"></div>

<!-- 골드 결제 -->
<div id="purchase_gold" class="modal fade" tabindex="-1" role="dialog">
</div><!-- /.modal -->

<!-- 비트코인 결제 -->
<div id="purchase_bitcoin" class="modal fade" tabindex="-1" role="dialog">
</div><!-- /.modal -->

<!-- 페이팔 결제 -->
<div id="purchase_paypal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">PAYPAL</h4>
            </div>
            <div id="frame_paypal"  class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>