<?php

$qry = "select * from members where m_idx = $u_idx";
$result = mysqli_query($conn, $qry);
$arr = mysqli_fetch_assoc($result);
?>
<div id="back_box" class="dp_cotent_box">
    <div class="container dp_main_sidemenu">
        <div class="col-md-2">
            <div class="create_title">
                <span class="dp_title">My Page</span>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Menu</div>
                <div class="list-group">
                    <a href="index.php?menu=my&sub=account" class="list-group-item sub_account">My Account</a>
                    <a href="index.php?menu=my&sub=cs" class="list-group-item sub_cs">My 1:1 Ticket</a>
                    <a href="index.php?menu=my&sub=gold" class="list-group-item sub_gold">Gold history</a>
                    <a href="index.php?menu=my&sub=bitcoin" class="list-group-item sub_bitcoin">Bit-Coin history</a>
                </div>
            </div>

        </div>
        <div class="col-md-10 dp_my_body">
            <div>
                <ul>
                    <li>
                        <img src="<?= $avatar ?>" style="width:80px; height:80px; border:1px solid #aaa">
                    </li>
                    <li>
                        <div>
                            <span>NICK NAME : </span> 
                            <span class="dp_font_accent">
                                <?= $arr['m_name'] ?>
                            </span>
                        </div>
                        <div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#upload">Upload Photo</button>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
            <div class="dp_my_content">
                <?php
                if ($sub == 'account') {
                    require_once 'sub/account.php';
                } else if ($sub == 'cs') {
                    require_once 'sub/cs.php';
                } else if ($sub == 'gold') {
                    require_once 'sub/gold.php';
                } else if ($sub == 'ticket') {
                    require_once 'sub/ticket.php';
                } else if ($sub == 'bitcoin') {
                    require_once 'sub/bitcoin.php';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div id="cal_content"></div>

<div id="upload" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Upload Photo</h4>
            </div>
            <form id='upload_form' action="component/upload_avatar.php" method="post" enctype="multipart/form-data">
                <div id="chg_pw_content">
                    <div class="modal-body">
                        <p>Please choose your image file on your PC.</p>
                        <div>
                            <input type="file" class="btn btn-warning" name="avatar" >
                        </div>
                        <p>
                            <br>* Recommend 80px * 80px sizes.
                            <br>* Supported Formats: PNG, JPG, GIF
                            <br>* Under 1MB
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Upload</button>                       
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
