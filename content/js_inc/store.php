<script src="<?= INC_PUBLIC ?>js/jquery.number.min.js"></script>
<script>
    $(document).ready(function () {

        var data = {};
        var amount;
        var bit;

        $('#gold_purchase').on('click', 'li', function () {
            index = $(this).attr('data-index');
            //
            data = {'type': 'gold'};
            // 폼 가져오기
            $.get('ajax/form_store.php', data, function (res) {
                $('#purchase_gold').html(res);
                var purchase = '$0';
                var src = 'public/images/gold_m' + index + '.png';
                switch (index) {
                    case '1':
                        amount = 5;
                        purchase = '$5.00';
                        break;
                    case '2':
                        amount = 10;
                        purchase = '$9.50';
                        break;
                    case '3':
                        amount = 50;
                        purchase = '$45.50';
                        break;
                    case '4':
                        amount = 100;
                        purchase = '$88.00';
                        break;
                }

                $('#img_paypal').attr('src', '<?= INC_PUBLIC ?>images/store_paypal.png');
                $('.img_gold > img').attr('src', src);
                $('.btn_confirm').text(purchase);
                $('#purchase_gold').modal('show');
                data = {
                    'index': index,
                    'amount': amount
                };
            });
        });

        var bitcoinAddress = '<?= $arr[1] ?>';
        //
        $('#bitcoin_purchase').on('click', 'li', function () {
            //
            index = $(this).attr('data-index');
            bit = $(this).attr('data-bit-amount');
            //
            data = {'type': 'bitcoin'};
            // 폼 가져오기
            $.get('ajax/form_store.php', data, function (res) {
                $('#purchase_bitcoin').html(res);
                $('#my_bitcode').html('<?= $my_bitcode ?>');
                $('#bitcoin_code').val('<?= $arr[1] ?>');
                //
                if (!bitcoinAddress) {
                    var go_my = confirm('Bit-coin address did not entered.\nDo you want to enter now?');
                    if (go_my) {
                        location.replace('index.php?menu=my&sub=account');
                        return false;
                    } else {
                        return false;
                    }
                }
                //
                var purchase = '$0';
                var src = 'public/images/bitcoin_m' + index + '.png';
                switch (index) {
                    case '1':
                        amount = 10;
                        purchase = '10 G';
                        break;
                    case '2':
                        amount = 50;
                        purchase = '50 G';
                        break;
                    case '3':
                        amount = 100;
                        purchase = '100 G';
                        break;
                    case '4':
                        amount = 500;
                        purchase = '500 G';
                        break;
                }

                $('.bitcoin_amount > span').text(bit);
                $('.img_gold > img').attr('src', src);
                $('.btn_bitcoin').text(purchase);
                $('#purchase_bitcoin').modal('show');
                data = {
                    'index': index,
                    'address': bitcoinAddress,
                    'amount': amount,
                    'bit': bit,
                    'rate': '<?= $bitcoin_price ?>'
                };
            });


        });

        $(document).on('click', '.btn_confirm', function () {
            if (!$('#gold_step_1:checked').val()) {
                alert('[STEP1] Please input your Bit-coin address.');
                return false;
            } else if (!$('#gold_step_2').is(':checked')) {
                alert('[STEP2] Please check the checkbox.');
                return false;
            }

            $.get('api/paypal/payment-experience/CreateWebProfile.php', function (id) {
                data.p_id = id;
                $('.modal-dialog').removeClass('modal-lg');
                $('.gold_msg').html('Wait a few seconds..');
                $.post('api/paypal/payments/CreatePaymentUsingPayPal.php', data, function (res) {
                    if (res) {
                        location.replace(res);
                    }
                });
            });

        });

        $(document).on('click', '.btn_bitcoin', function () {

            if (!bitcoinAddress) {
                alert('[STEP1] Please select the purchase method.');
                return false;
            } else if (!$('#bitcoin_step_2').is(':checked')) {
                alert('[STEP2] Please check the checkbox.');
                return false;
            }

            $('.modal-dialog').removeClass('modal-lg');

            var next_step = '<ul class="bitcoin_detail">';
            next_step += '<li>Your Bitcoin Address: <span>' + data.address + '</span></li>';
            next_step += '<li>PAY: <span>' + data.amount + ' Gold</span></li>';
            next_step += '<li>USD/BTC: <span><?= $bitcoin_price ?> BTC</span> (<?= $bitcoin_market ?>)</li>';
            next_step += '<li>BITCOIN PURCHASE: <span>' + data.bit + ' BTC</span></li>';
            next_step += '</ul>';

            $('.bitcoin_msg').html(next_step);
            $('.modal-footer > button').before('<button class="btn btn-primary btn_bitcoin_purchase">Purchase</button>');
        });

        $(document).on('click', '.btn_bitcoin_purchase', function () {
            $.post('ajax/req_bitcoin.php', data, function (res) {
                if (res === '100') {
                    alert('Complete');
                    $('#purchase_bitcoin').modal('hide');
                    location.replace('index.php?menu=store');
                    return false;
                } else if (res === '501') {
                    alert('Not enough bit-coin what we have.');
                    location.replace('index.php?menu=store');
                    return false;
                } else if (res === '503') {
                    alert('Not enough golds what you have.');
                    location.replace('index.php?menu=store');
                    return false;
                } else {
                    alert('Error Occuered');
                    location.replace('index.php?menu=store');
                    return false;
                }
            });
        });

        $(document).on('click', '.btn_edit_code', function () {
            location.replace('index.php?menu=my&sub=account');
        });
<?php
if ($sub == null) {
    ?>
            setInterval(function () {
                $.get('ajax/bit_coin.php', function (res) {
                    if (res === '500') {
                        alert('Error occurred');
                        return false;
                    }
                    if (typeof res === 'number') {
                        if (res % 1 === 0) {
                            res = $.number(res);
                        } else {
                            // float
                            res = $.number(res, 3);
                        }
                    }

                    $('#bitcoin_limit').text(res + ' BTC');
                });
            }, 1000 * 60 * 10);
    <?php
} else {
    ?>
            $('.dp_store_body').css('height', '600px');
    <?php
}
?>

    });
</script>
<script>
    $(document).ready(function () {
        function BBS(row, paging, table) {
            var total_page;
            var total_row = row;
            var total_paging = paging;
            var tr_id = '#' + table;
            var history_tr = $(tr_id).find('tr');

            var arr_cout;
            var now_page = 0;

            // GETTER & SETTER ////////////////    
            this.get_total_row = function () {
                return total_row;
            };
            this.set_total_row = function (row) {
                return total_row = row;
            };

            this.get_total_paging = function () {
                return total_paging;
            };
            this.set_total_paging = function (paging) {
                return total_paging = paging;
            };

            this.get_tr_id = function () {
                return tr_id;
            };

            this.get_now_page = function () {
                return now_page;
            };
            this.set_now_page = function (page) {
                return now_page = page;
            };

            this.total_page = history_tr.length;
            this.arr_cout = Math.ceil(this.total_page / this.get_total_row());

            this.tr_content();
            this.page_navi();
            this.click_num();
            this.click_page_move();
        }

        BBS.prototype.tr_content = function () {
            var now_index;
            var till_index;

            if (this.get_now_page() === 0) {
                now_index = 0;
                till_index = this.get_total_row();
            } else {
                now_index = this.get_now_page() * this.get_total_row();
                till_index = (this.get_now_page() * this.get_total_row()) + this.get_total_row();
            }

            $(this.get_tr_id()).find('tr').each(function (index) {
                if (index < till_index && index >= now_index) {
                    $(this).css('display', 'table-row');
                } else {
                    $(this).css('display', 'none');
                }
            });
        };

        BBS.prototype.page_navi = function () {
            var navi_num = '';
            var navi_page_num = this.get_now_page();

            for (var i = 0; i < this.get_total_paging(); i++) {
                navi_page_num++;

                if (i === 0) {
                    if (navi_page_num > this.get_total_paging()) {
                        navi_num += '<li class="page_move" data-num-next="' + (navi_page_num - (this.get_total_paging() + 1)) + '" style="cursor:pointer">';
                        navi_num += '<a aria-label="Previous">';
                        navi_num += '<span aria-hidden="true">&laquo;</span>';
                        navi_num += '</a>';
                        navi_num += '</li>';
                    }
                }

                if (navi_page_num < this.arr_cout + 1) {
                    navi_num += '<li class="paging" data-num-index="' + parseInt(navi_page_num) + '"><a style="cursor:pointer">' + parseInt(navi_page_num) + '</a></li>';
                }

                if (i === this.get_total_paging() - 1) {
                    if (navi_page_num < this.arr_cout) {
                        navi_num += '<li class="page_move" data-num-next="' + (navi_page_num) + '" style="cursor:pointer">';
                        navi_num += '<a aria-label="Next">';
                        navi_num += '<span aria-hidden="true">&raquo;</span>';
                        navi_num += '</a>';
                        navi_num += '</li>';
                    }
                }
            }
            $('.pagination').html(navi_num);
        };

        BBS.prototype.click_num = function () {
            var bbs_this = this;
            $('.paging').click(function () {
                var index = $(this).attr('data-num-index');
                bbs_this.set_now_page(index - 1);

                bbs_this.tr_content();
            });
        };
        BBS.prototype.click_page_move = function () {
            var bbs_this = this;
            $('.page_move').click(function () {
                var index = $(this).attr('data-num-next');
                bbs_this.set_now_page(index);

                bbs_this.tr_content();
                bbs_this.page_navi();
                bbs_this.click_num();
                bbs_this.click_page_move();
            });
        };

        var test = new BBS(14, 10, 'table');
    });
</script>