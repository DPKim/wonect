<?php
if (!isset($sub)) {
    $sub = 'upcoming';
}
?>
<div id="back_box" class="dp_cotent_box">
    <div class="container dp_main_sidemenu">
        <div class="create_title">
            <span class="dp_title" >My Contests</span>
        </div>
        <div class=" dp_bg_black" style="height: 50px; border-radius: 10px 10px 0px 0px">
            <ul class="ul_game_cate">
                <li class="checked" data-index="0">
                    ALL
                </li>
                <li data-index="2">
                    MLB
                </li>
                <li data-index="3">
                    LOL
                </li>
                <li data-index="4">
                    SOC
                </li>
                <li data-index="1">
                    NBA
                </li>
                <li data-index="5">
                    GOLF
                </li>
                <li data-index="6">
                    NHL
                </li>
                <li data-index="7">
                    NAS
                </li>
                <li data-index="8">
                    MMA
                </li>
                <li data-index="9">
                    NFL
                </li>
                <li data-index="10">
                    CFL
                </li>
            </ul>
        </div>

        <div class="bg_content dp_paddign15 text-center">
            <ul id="tab_contest">
                <li>
                    Upcoming
                </li>
                <li>
                    Live
                </li>
                <li>
                    Finish
                </li>
            </ul>
            <?php
            switch ($sub) {
                case 'upcoming':
                    ?>
                    <div style="float: right; padding-top: 12px">
                        Possible Winning Amount : 
                        <span style="color:orange; font-size: 16px; font-weight: 500">G <span id="t_winning">0</span></span>
                    </div>
                    <?php
                    break;
                case 'live':
                    ?>
                    <div style="float: right; padding-top: 12px">
                        Live Winning Amount : 
                        <span style="color:orange; font-size: 16px; font-weight: 500">G <span id="t_winning">0</span></span>
                    </div>
                    <?php
                    break;
            }
            ?>            
            <div class="clearfix"></div>
            <div class="lu_tb">
                <?php
                require_once "sub/$sub.html";
                ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="cal_content"></div>
<!-- 게임 디테일 -->
<div id="draftgame" style="display: none">
    <div class="light_room"></div>
    <div class="lb_container contest_detail"></div>
</div>