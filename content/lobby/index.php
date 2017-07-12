<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$cate = filter_input(INPUT_GET, 'cate');
$league_name = filter_input(INPUT_GET, 'name');

if (!isset($cate)) {
    $cate = 2;
    $league_name = 'MLB';
}

$lobby_game = new CreateGame(0, $cate);
?>

<div id="back_box" class="dp_cotent_box">
    <div class="container dp_main_sidemenu">
        <div class="col-md-2">
            <div class="dp_main_time">
                <div class="dp_font_fff">PURCHASABLE</div>
                <span>
                    <i class="fa fa-btc" aria-hidden="true"></i> 
                    <?php
                    $qry_btc = "select bc_amount from bitcoin";
                    $result_btc = mysqli_query($conn, $qry_btc);
                    if ($result_btc) {
                        $arr = mysqli_fetch_array($result_btc);
                        echo numberFormat_for_float($arr[0]);
                    }
                    ?> 
                    BTC
                </span> 
            </div>
            <div class="input-group">
                <span class="input-group-addon"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                <input type="text" class="form-control" id="search_list">
            </div>

            <div class="clearfix"></div>
            <div class="dp_margin_top10"></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Contest Types
                </div>
                <div id="sub_menu">
                    <a href="#" class="list-group-item active">All</a>
                    <a href="#" class="list-group-item" data-index="1">Free Zone</a>
                    <a href="#" class="list-group-item" data-index="2">Below Top5</a>
                    <a href="#" class="list-group-item" data-index="3">Double Up</a>
                    <a href="#" class="list-group-item" data-index="4">Triple Up</a>
                    <a href="#" class="list-group-item" data-index="5">10x Up</a>
                    <a href="#" class="list-group-item" data-index="6">50/50</a>
                    <a href="#" class="list-group-item" data-index="7">Multi Entry</a>
                </div>
            </div>
        </div>
        <div class="bg_content col-md-10" style="padding: 5px 15px 15px 15px">           
            <?= $lobby_game->make_tab_date($today_date, $locale) ?>
        </div>
        <div class="col-md-10 dp_lobby_tb_top">
            <table class="dp_draft_table dp_bg_42698e">
                <tbody class="dp_font_fff">
                    <tr style="font-size:12px">
                        <td style="width:6%; text-align: center">Sport</td>
                        <td style="text-align: center">Contest</td>
                        <td style="width:8%; text-align: center">Entry Fee</td>
                        <td style="width:8%; text-align: center">Total Prizes</td>
                        <td style="width:9%; text-align: center">Entries</td>
                        <td style="width:16%; text-align: center">Live</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-10 dp_lobby_tb_body">
            <table width="100%">
                <tbody id="list_lobby" class="dp_font_444"></tbody>
            </table>

        </div>
    </div>
</div>

<div id="cal_content"></div>

<!-- 게임 디테일 -->
<div id="draftgame" >
    <div class="light_room"></div>
    <div class="lb_container contest_detail"></div>
</div>