<?php
if (!$sub) {
    ?>
    <div id="back_box" class="dp_cotent_box">
        <div class="container dp_main_sidemenu">
            <div class="create_title">
                <span class="dp_title" >EVENTS</span>
            </div>
            <ul  class="event_container">
                <li>
                    <ul class="event_box" data-index="event_01">
                        <li>FREE ENTRY</li>
                        <li>
                            <img src="public/images/banner/banner_main_01.png">
                        </li>
                        <li>
                            <div>
                                Enjoy free contests and wind gold <br>
                                Join contest. Win Bit-Coin<br>
                            </div>
                            <div>
                                <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> 
                                Date : Always
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul class="event_box" data-index="event_02">
                        <li>FIRST PURCHASE</li>
                        <li>
                            <img src="public/images/banner/banner_main_02.png" >
                        </li>
                        <li>
                            <div>
                                Get double bonus ticket <br>
                                100 Gold + 100 Tickets<br>
                            </div>
                            <div>
                                <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> 
                                Date : 2017.04.01 ~ 2017.12.31
                            </div>
                        </li>
                    </ul>
                </li>


            </ul>
            <div class="clearfix padding20"></div>
        </div>
    </div>
    <div id="cal_content"></div>
    <?php
} else {
    ?>
    <div id="back_box" class="dp_cotent_box">
        <div class="container dp_main_sidemenu">
            <?php
            require_once 'sub/' . $sub . '.php';
            ?>
            <div class="clearfix padding10"></div>
            <button class="btn btn-default btn_goBack">BACK</button>
            <div class="clearfix padding10"></div>
        </div>
    </div>
    <div id="cal_content"></div>
    <?php
}
?>
