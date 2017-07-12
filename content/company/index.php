<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
?>
<div id="back_box" class="dp_cotent_box">
    <div class="container dp_main_sidemenu">
        <div class="col-md-2">
            <div class="create_title">
                <span class="dp_title">COMPANY</span>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Menu</div>
                <div class="list-group">
                    <a href="index.php?menu=company&sub=notice" class="list-group-item sub_notice">Notice</a>
                    <a href="index.php?menu=company&sub=contact" class="list-group-item sub_contact">Contact US</a>
                    <a href="index.php?menu=company&sub=terms" class="list-group-item sub_terms">Term of Use</a>
                    <a href="index.php?menu=company&sub=privacy" class="list-group-item sub_privacy">Privacy Policy</a>
                </div>
            </div>

        </div>
        <div class="col-md-10 dp_store_body">
            <div class="dp_store_content">
                <?php
                if ($sub !== 'notice') {
                    require_once 'sub/' . $sub . '.html';
                } else {
                    require_once 'sub/notice.php';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div id="cal_content"></div>