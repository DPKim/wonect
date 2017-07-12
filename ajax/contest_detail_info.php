<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include_once '../inc/config.php';

$g_idx = filter_input(INPUT_POST, 'index');

// 게임 가져오기 
$qry_get_game = "select * from game ";
$qry_get_game .= "left join join_contest on jc_game = g_idx ";
$qry_get_game .= "left join members on jc_u_idx = m_idx ";
$qry_get_game .= "where g_idx = $g_idx ";
$qry_get_game .= "order by jc_u_idx desc ";

$result_get_game = mysqli_query($conn, $qry_get_game);
$count_get_game = mysqli_num_rows($result_get_game);

$my_multi = 0;
$betting_user = array();

for ($i = 0; $i < $count_get_game; $i++) {
    $arr_get_game = mysqli_fetch_assoc($result_get_game);
    $rank_reward = new RankReward($arr_get_game['g_size'], $arr_get_game['g_fee'], $arr_get_game['g_prize']);

    if ($i == 0) {
        // 현재 게임이 시작되었는지 여부 체크하기
        $game_time = strtotime($arr_get_game['g_date']);
        $now_time = strtotime($today); // 현재 시간 보다 30분 더 경과 시킴
        $diff_time = $game_time - $now_time;
        if ($diff_time > 0) {
            
        }

        $fee = numberFormat_for_float($arr_get_game['g_fee']);
        $total_prize = numberFormat_for_float($rank_reward->total_reward);
        $count_top_prize = count($rank_reward->make_rank_arr());

        $title = $arr_get_game['g_name'];

        // 상금
        foreach ($rank_reward->make_rank_arr() as $value) {
            $rank_prize .=<<<RP
                <div class="dp_font_444 dp_font_11px text-left padding10 dp_border_bottom_bt_1px">
                    <span class="dp_float_left">{$value['rank']}</span>
                    <span class="dp_float_right">{$value['reward']} G</span>
                    <div class="clearfix"></div>
                </div>
RP;
        }
    }

    if ($arr_get_game['jc_u_idx'] == $u_idx) {
        $my_multi++;
    }
    array_push($betting_user, $arr_get_game['m_name']);
}

$final_betting_user = array_unique($betting_user);
$enter_user = '';

foreach ($final_betting_user as $key => $value) {
    if ($value !== null) {
        $enter_user .= <<< EU
            <li class="lb_li_entrants padding5 dp_bg_efefef">$value</li>
EU;
    }
}
?>
<div class="lobby_detail_box" style="border-radius: 20px">
    <h4 class="dp_font_333"><?= $title ?></h4>
    <div class="lb_detail_top_l">
        <table class="dp_draft_table">
            <tbody class="dp_font_fff">
                <tr>
                    <td class="dp_bg_111 text-center">Total Prize</td>
                    <td class="dp_bg_111 text-center">Entry Fee</td>
                    <td class="dp_bg_111 text-center">Entries</td>
                    <td class="dp_bg_111 text-center">Max Multi</td>
                    <td class="dp_bg_111 text-center">My Entries</td>
                </tr>
                <tr style="background: #aaa">
                    <td id="detail_info_total_prize"><?= $total_prize ?> G</td>
                    <td id="detail_info_entry_fee"><?= $fee ?> G</td>
                    <td id="detail_info_entry">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> 
                        <?= $arr_get_game['g_entry'] ?>/<?= $arr_get_game['g_size'] ?>
                    </td>
                    <td id="detail_info_multi_entry">
                        <?= $arr_get_game['g_multi_max'] ?>
                    </td>
                    <td id="detail_info_my_entry">
                        <?= $my_multi ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="lb_detail_top_r padding10 dp_bg_black">
        <div class="dp_font_fff text-left">
            <?php
            // 시간대에 맞는 날짜로 변경
            $db_class = new DB_conn;
            $newdate = $db_class->change_timezone($arr_get_game['g_date'], $arr_get_game['g_timezone'], $locale);
            ?>
            Live in : <?= $newdate['day'] ?>
            <div id="detail_count_down" data-date="<?= $newdate['day'] ?>" style="color: #ff6529; font-size: 14px; font-weight: 800"></div>
        </div>
    </div>
    <div style="clear: both; margin-bottom: 10px"></div>
    <div class="lb_detail_info_l">
        <div style="margin-bottom: 10px">
            <div class="dp_font_333 text-left">Summary</div>
            <div class="lb_summary dp_bg_white">
                <div class="dp_font_444 padding20 text-left">
                    This <?= $arr_get_game['g_size'] ?>-player contest features <?= $total_prize ?>G in total prizes and pays out the top <?= $count_top_prize ?> finishing positions. <br>
                    First place wins <?= numberFormat_for_float($rank_reward->make_rank_arr()[0]['reward']) ?>G.<br>
                    * Depending on the entry of finalists, the prize gold may be different.
                </div>
            </div>
        </div>
        <div>
            <div class="dp_font_333 text-left">Entrants</div>
            <div class="lb_entrants dp_bg_white">
                <ul class="dp_font_333 padding10">
                    <?= $enter_user ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="lb_detail_info_r">
        <div class="dp_font_333 text-left">Prize</div>
        <div class="lb_prize dp_bg_white">
            <?= $rank_prize ?>
        </div>
    </div>
    <div style="clear: both; margin-bottom: 10px"></div>
    <div style="text-align: right">
        <button class="btn btn-default btn_detail_close">CLOSE</button>
        <button class="btn btn-primary btn-draft" data-game="<?= $arr_get_game['g_idx'] ?>">ENTER</button>
    </div>
</div>