<?php

?>
<div id="back_box" class="dp_cotent_box">
    <div class="container dp_main_sidemenu">
        <div class="col-md-2">
            <div class="create_title">
                <span class="dp_title">SPORTS</span>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Menu</div>
                <div class="list-group">
                    <a href="index.php?menu=sport&sub=htp" class="list-group-item">How to Play</a>
                    <a href="index.php?menu=sport&sub=soc" class="list-group-item">SOC</a>
                    <a href="index.php?menu=sport&sub=nba" class="list-group-item">NBA</a>
                    <a href="index.php?menu=sport&sub=mlb" class="list-group-item">MLB</a>
                    <a href="index.php?menu=sport&sub=nfl" class="list-group-item">NFL</a>
                    <a href="index.php?menu=sport&sub=nhl" class="list-group-item">NHL</a>
                    <a href="index.php?menu=sport&sub=lol" class="list-group-item">LOL</a>
                    <a href="index.php?menu=sport&sub=cfl" class="list-group-item">CFL</a>
                    <a href="index.php?menu=sport&sub=golf" class="list-group-item">GOLF</a>
                    <a href="index.php?menu=sport&sub=mma" class="list-group-item">MMA</a>
                    <a href="index.php?menu=sport&sub=nas" class="list-group-item">NAS</a>
                </div>
            </div>

        </div>
        <div class="col-md-10 dp_store_body">
            <div class="dp_store_content">
                <?php
                if ($sub == 'soc') {
                    require_once 'sub/soc.html';
                } else if ($sub == 'nba') {
                    require_once 'sub/nba.html';
                } else if ($sub == 'mlb') {
                    require_once 'sub/mlb.html';
                } else if ($sub == 'nfl') {
                    require_once 'sub/nfl.html';
                } else if ($sub == 'nhl') {
                    require_once 'sub/nhl.html';
                } else if ($sub == 'lol') {
                    require_once 'sub/lol.html';
                }  else if ($sub == 'htp') {
                    require_once 'sub/htp.html';
                } else if ($sub == 'cfl') {
                    require_once 'sub/cfl.html';
                } else if ($sub == 'golf') {
                    require_once 'sub/golf.html';
                } else if ($sub == 'mma') {
                    require_once 'sub/mma.html';
                } else if ($sub == 'nas') {
                    require_once 'sub/nas.html';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div id="cal_content"></div>