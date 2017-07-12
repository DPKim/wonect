<?php
include_once '../inc/config.php';
include_once CONTENT . 'class/class_player_detail.php';

$p_id = filter_input(INPUT_POST, 'index');
$cate = filter_input(INPUT_POST, 'category');
//
$p_detail = new PlayerDetail($p_id, $cate);
$player_info = $p_detail->$cate();
?>
<div class="player_detail_box" style="border-radius: 20px">
    <?= $player_info['primary_info'] ?>
    <div style="clear: both; margin-bottom: 10px"></div>
    <div style="margin-bottom: 10px">
        <div class="dp_font_333 text-left">Last 5 Game Results</div>
        <div class="">
            <table  class="dp_draft_table dp_font">
                <?= $player_info['game_result_info'] ?>
            </table>
        </div>
    </div>   
    <div style="clear: both; margin-bottom: 10px"></div>
    <div style="text-align: right">
        <button class="btn btn-default btn_detail_close">CLOSE</button>
    </div>
</div>