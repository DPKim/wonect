<?php
if ($chk_login) {
    header('Location: index.php?menu=lobby');
}
?>
<div class="container text-center dp_login">
    <a href="index.php?meun=lobby"><img src="<?= INC_PUBLIC ?>images/logo.png"></a>
    <h1>ONE-DAY FANTASY&nbsp;SPORTS</h1>
    <h2>JOIN SPO-BIT. WIN&nbsp;CASH.</h2>
    <div class="jumbotron" style="width: 700px; margin: 30px auto; color:#222;">
        <div style="text-align: left; font-size: 30px">
            <h3 class="modal-title" >SIGN IN</h3>
        </div>
        <div style="text-align: left; font-size: 20px; margin-top: 40px">               
            <div class="form-group">
                <label>User ID (e-mail)</label>
                <input type="email" id="id_login" class="form-control" placeholder="ID">
            </div>
            <div class="form-group">
                <label>Passwords</label>
                <input type="password" id="pw_login" class="form-control" placeholder="Passwords">
            </div>
            <div>
                <a href="#" data-toggle="modal" data-target="#findPw">Forgot your password?</a>
            </div>
            <button type="button" class="btn btn-primary btn-lg btn-login" style="margin-top: 10px" onclick="login_process()">SIGN IN</button>
        </div>
        <div style="width: 40px; width: 100%; position: relative; margin: 20px 0 20px 0">
            <div style="height: 30px; border-bottom: 2px dashed #222; width: 100%; position: absolute"></div>
            <p style="width: 50px; left: 45%; top:4px; padding: 10px; background: #eee; position: absolute">OR</p>
        </div>
        <div style="margin-top: 80px">
            <div style="width: 100%">
                <div id="login_facebook" class="btn_sign facebook">
                    <i class="fa fa-facebook" aria-hidden="true"></i>
                    Facebook
                </div>
                <div class="btn_sign google notyet">
                    <i class="fa fa-google-plus" aria-hidden="true"></i>
                    Google+
                </div>
                <div class="btn_sign twitter notyet">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                    Twitter
                </div>
                <div class="btn_sign yahoo notyet">
                    <i class="fa fa-yahoo" aria-hidden="true"></i>
                    Yahoo
                </div>               
            </div>
        </div>
    </div>
    <div>
        © 2016 - 2017 Aman International Inc., All Rights Reserved
    </div>
</div>

<div id="findPw" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">RESET PASSWORD</h4>
            </div>
            <div id="chg_pw_content">
                <div class="modal-body">
                    <p class="comment">We will send you an email containing a link to complete this process and reset your password.</p>
                    <h4 class="small_title">Your e-mail</h4>
                    <div class="input_form">
                        <input id='email' type="email" class="form-control" placeholder="E-mail Address">
                        <span id="msg" style="color:red"></span>
                    </div>
                    <br>
                    <div id="g-recaptcha"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn_find_pw">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>