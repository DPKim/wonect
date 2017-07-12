<?php ?>
<div>
    <h3>Account Detail</h3>
    <ul>
        <li>
            Gold Balance : 
            <span class="dp_font_accent"><?= numberFormat_for_float($arr['m_deposit']) ?> Gold</span>
        </li>
        <li>
            Tickets Balance : 
            <span class="dp_font_accent">0 Tickets</span>
        </li>
    </ul>
</div>
<hr>
<div>
    <h3>Personal Information</h3>
    <ul>
        <li>
            e-mail : 
            <span class="dp_font_accent"><?= $arr['m_id'] ?></span>
        </li>
        <li>
            <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#chgPw">change password</button>
        </li>
    </ul>
</div>
<div>
    <h3>Bitcoin Address</h3>
    <ul>
        <li>
            <div style="float: left; width: 600px; margin-right: 10px">
                <input type="text" class="form-control" id="bitcoin" value="<?=$arr['m_bitcoin_code']?>" placeholder="PLEASE INPUT YOUR BITCOIN ADDRESS">
            </div>
            <button type="submit" class="btn btn-default btn_edit_bitcoin">Regist</button>
            <div class="clearfix"></div>
        </li>
    </ul>
</div>

<!-- 비밀번호 수정 -->
<div id="chgPw" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Passwords</h4>
            </div>
            <div id="chg_pw_content">
                <div class="modal-body">
                    <p>Please enter the password now you using.</p>
                    <div>
                        <input id='now_pw' type="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn_chg_pw">Change Passwords</button>
                </div>
            </div>
        </div>
    </div>
</div>
