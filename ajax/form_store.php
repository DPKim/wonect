<?php
$type = filter_input(INPUT_GET, 'type');
if ($type == 'bitcoin') {
    ?>
    <!-- 비트코인 결제 -->
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">PURCHASING BITCOIN</h4>
            </div>
            <div class="modal-body bitcoin_msg">
                <ul class="store_ul">
                    <li class="height80">STEP1</li>
                    <li class="height80">
                        <div style="vertical-align: middle">
                            <span id="my_bitcode"></span>
                            <input type="hidden" id="bitcoin_code" value="">
                        </div>
                    </li>
                    <li class="height210">STEP2</li>
                    <li class="height210">
                        <div style="padding: 10px; background-color: #efefef">
                            (a) While playing “Spo-Bit”, you will have the opportunity to visit our online store (“STORE”) and can buy BitCoin.
                            <br>
                            (b) If you elect to purchase BitCoin, you warrant that (i) you have the legal capacity (if you are a minor, your legal guardian has granted their consent) to purchase BitCoin, (ii) your use of gold (iii) all information that you submitted for your transaction is true and accurate.
                            <br>
                            (c) YOU UNDERSTAND AND AGREE THAT ONCE YOU AUTHORIZE US TO BUY BITCOIN, YOU THROUGH THE PAYMENT SERVICE YOU CHOOSE FOR A CERTAIN AMOUNT, SUCH AMOUNT SHALL UNDER NO CIRCUMSTANCES BE REFUNDABLE INCLUDING, WITHOUT LIMITATION, UPON
                        </div>
                        <div>
                            <input type="checkbox" id="bitcoin_step_2"> AGREE
                        </div>
                    </li>
                    <li class="height240">STEP3</li>
                    <li class="height240">
                        <div class="img_gold">
                            <div class="bitcoin_amount">
                                <i class="fa fa-btc" aria-hidden="true"></i> <span></span>
                            </div>
                            <img src="">
                        </div>
                        <div>
                            <ul style="padding: 0px; margin: 0 0 10px 0">
                                <li style="list-style: disc; display: list-item">
                                    Normally, Transection complete will be within 24 hours, <br>
                                    Max transection time is 48 hours.
                                </li>
                                <li style="list-style: disc; display: list-item">
                                    If you put wrong information of your BitCoin Address, <br>
                                    will be canceled transection and refund your gold.
                                </li>
                                <li style="list-style: disc; display: list-item">
                                    You can check transection status on BitCoin History
                                </li>
                                <li style="list-style: disc; display: list-item">
                                    It cannot be refunded when; <br>
                                    (a) It is completed transection. <br>
                                    (b) 24 hours passed from date of purchase <br>
                                </li>
                            </ul>
                            <button class="btn btn-warning btn-lg btn_bitcoin btn_gold">$5.00</button>
                        </div>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <?php
} else if($type == 'gold'){
    ?>
       <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">PURCHASING GOLD</h4>
            </div>
            <div class="modal-body gold_msg">
                <ul class="store_ul">
                    <li class="height80">STEP1</li>
                    <li class="height80">
                        <div style="vertical-align: middle">
                            <input type="radio" id="gold_step_1" value="1">
                            <img id="img_paypal">
                        </div>
                    </li>
                    <li class="height180">STEP2</li>
                    <li class="height180">
                        <div style="padding: 10px; background-color: #efefef">
                            (a) While playing “Spo-Bit”, you will have the opportunity to visit our online store (“STORE”) and use virtual “cash” (“GOLD”) to get a license to use a variety of virtual items (“Cash Item(s) and Entry Fee(s)”) that can be used while playing “Spo-Bit”.
                            (b) If you select to purchase GOLD, you warrant that (i) you have the legal capacity (if you are a minor, your legal guardian has granted their consent) to purchase GOLD and use GOLD to get a license to use a variety of Cash Item(s) and Entry Fee(s), (ii) your use of a credit card or other payment service on the Site is authorized, and (iii) all information that you submitted for your transaction is true and accurate.
                        </div>
                        <div>
                            <input type="checkbox" id="gold_step_2"> AGREE
                        </div>
                    </li>
                    <li class="height210">STEP3</li>
                    <li class="height210">
                        <div class="img_gold"><img></div>
                        <div>
                            It cannot be refunded when<br>
                            - After it has been consumed<br>
                            - After 7 days of purchase<br><br>
                            <button class="btn btn-warning btn-lg btn_confirm btn_gold">$5.00</button>
                        </div>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <?php
}
?>
