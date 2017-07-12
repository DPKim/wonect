<?php

$g_idx = filter_input(INPUT_GET, 'index');
$type = filter_input(INPUT_GET, 'type');
$lu_idx = filter_input(INPUT_GET, 'lu');

if (!$type) {
    $type = 0;
}

$draftgame = new Function_Draftgame($g_idx, $type, $lu_idx, $u_idx);
$reward = new RankReward($draftgame->arr['g_size'], $draftgame->arr['g_fee'], $draftgame->arr['g_prize']);
$total_prize = $reward->total_reward;

?>
<div id="back_box" class="dp_cotent_box">
    <div class="container dp_main_sidemenu">
        <div class="create_title">
            <span class="dp_title"><?= $draftgame->arr['g_name'] ?></span>
        </div>
        <div>
            <div class="bg_content text-center padding50">
                <div class="section-details2">
                    <div class="dp_font_5ea600">Entries :</div> 
                    <span class="dp_font_fff"><?= $draftgame->arr['g_entry'] ?> / <?= $draftgame->arr['g_size'] ?></span>
                </div>
                <div class="section-details2 col-md-2 dp_margin_left10">
                    <div class="dp_font_5ea600">Entry Fee :</div> 
                    <span class="dp_font_fff">$<?= $draftgame->arr['g_fee'] ?></span>
                </div>
                <div class="section-details2 col-md-2 dp_margin_left10">
                    <div class="dp_font_5ea600">Total Prizes :</div> 
                    <span class="dp_font_fff">$<?= numberFormat_for_float($total_prize) ?></span>
                </div>
                <div class="section-details2 col-md-5 dp_margin_left10">
                    <div class="dp_font_5ea600">Live On :</div> 
                    <span class="dp_font_fff"><?= $draftgame->get_times()['week'] ?> <?= $draftgame->get_times()['day'] ?> / <?= $draftgame->get_times()['mon'] ?> <?= $draftgame->get_times()['hour'] ?> : <?= $draftgame->get_times()['min'] ?> </span>
                    <span id="game_date" class="dp_font_ff8a00 dp_font_16px dp_bold dp_margin_left10 btn-draft" data-main-timer="1" data-next-game = "<?= $draftgame->get_start_time() ?> <?= $draftgame->get_start_date() ?>"></span>
                </div>
                <div class="" style="width: 49%; text-align: left; float:left">
                    <div style="height: 30px">
                        <ul class="dp_tab_draft">
                            <li class="sort on" data-sort='All'>All</li>
                            <?= $draftgame->make_cate() ?>
                        </ul> 
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <table class="dp_draft_table">
                            <tr>
                                <td class="sort_table" style="width:10%">POS</td>
                                <td class="sort_table" style="width:25%">PLAYER</td>
                                <td class="sort_table" style="width:20%">TEAM</td>
                                <td class="sort_table" style="width:10%">Avg.P</td>
                                <td class="sort_table" style="width:10%">
                                    C.P
                                    <i class="fa fa-question-circle tooltip_cp" data-toggle="tooltip" title="Consumer preferences" aria-hidden="true"></i>
                                </td>
                                <td class="sort_table" style="width:0%"></td>
                                <td class="sort_table" style="width:25%; padding-left: 10px" align=left>SALARY</td>
                            </tr>
                        </table>
                    </div>
                    <div class="dp_draft_choice_player scroll">
                        <table id="table_list" class="dp_draft_table2 pick_player">
                            <tbody id="player_list" class="dp_draft_table2 pick_player dp_font_fff">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="dp_margin_left10" style="width: 49%; text-align: left; float:left">
                    <div class="btn-draft">
                        <span class="dp_draft_lineup">LINEUP</span>
                        <span class="dp_float_right dp_draft_salary">Rem. Salary: <span class="dp_font_fff">$<span class="total_salary"><?= $draftgame->remSalary ?></span></span> LINEUP</span>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <div>
                            <table class="dp_draft_table">
                                <tr>
                                    <td style="width:10%">POS</td>
                                    <td style="width:30%">PLAYER</td>
                                    <td style="width:30%">TEAM</td>
                                    <td style="width:30%">SALARY</td>
                                </tr>
                            </table>
                        </div>
                        <div class="dp_draft_choice_lineup">
                            <table class="dp_draft_table2">
                                <tbody class="dp_font_fff">
                                    <?= $draftgame->lineup_tb ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix dp_margin_top10"></div>
                        <div>
                            <button class="btn btn-default btn-sm btn-clear">Clear</button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="dp_margin_top10 text-right">
                <?= $draftgame->make_btn() ?>
            </div>
            <p></p>
        </div>
    </div>
</div>
<div id="cal_content"></div>

<!-- 선수 디테일 -->
<div id="player_detail" style="display: none;">
    <div class="light_room"></div>
    <div class="lb_container player_detail"></div>
</div>