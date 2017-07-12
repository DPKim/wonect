<?php
if ($chk_login) {
    header('Location: index.php?menu=lobby');
}
?>
<div class="dp_bg_gray_383739 dp_border_bottom_bt_3px">
    <div class="container">
        <div class="col-xs-offset-8 col-md-offset-10 text-center dp_paddign10">
            <div class="dp-xs-6">
                <a href="#" data-toggle="modal" data-target="#logIn">SIGN IN</a>
            </div>
            <div>
                <a>FAQs</a>
            </div>
        </div>
    </div>
</div>
<div class="dp_bg_gray_1b1a1c dp_border_bottom_bt_3px dp_height_570 dp_bg_main">
    <div class="container text-center dp_main_logo">
        <img src="<?= INC_PUBLIC ?>images/logo.png">
        <h1>ONE-DAY FANTASY&nbsp;SPORTS</h1>
        <h2>JOIN THE GAME. WIN&nbsp;CASH.</h2>
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#joinMember">PLAY NOW</button>
    </div>
</div>
<div class="dp_border_bottom_bt_3px">
    <div class="container text-center dp_main_info">
        <div class="col-md-4">
            <div class="circle">1</div>
            <div>
                <span>REGISTER</span><br>
                Opening an account is easy and convenient
            </div>
        </div>
        <div class="col-md-4">
            <div class="circle">2</div>
            <div>
                <span>CHOOSE YOUR CONTEST</span><br>
                Pick your sport and entry fee
            </div>
        </div>
        <div class="col-md-4">
            <div class="circle">3</div>
            <div>
                <span>CREATE YOUR LINE-UP</span><br>
                Choose the best players and follow your progress in real time
            </div>
        </div>
    </div>
</div>
<div class="dp_bg_white">
    <div class="container text-center dp_main_why dp_font_444">
        <h2>WHY Fantasy Sport Club?</h2>
        <div>
            <div class="dp-xs-4">
                <img src="https://d2tjpz01y5bfgl.cloudfront.net/lp/UK/DTI/time-icon.png" alt="">
                <span>ANYTIME</span>
                Contests run over one day or a few days. No season-long commitment.
            </div>
            <div class="dp-xs-4">
                <img src="https://d2tjpz01y5bfgl.cloudfront.net/lp/UK/DTI/sports-icon.png" alt="">
                <span>MORE SPORTS</span>
                Football, Golf, NFL, NBA, MMA, MLB, NHL, NASCAR &amp; e-Sports
            </div>
            <div class="dp-xs-4">
                <img src="https://d2tjpz01y5bfgl.cloudfront.net/lp/UK/DTI/stack-icon.png" alt="">
                <span>BIGGER PRIZES</span>
                With millions of players from the UK, Canada and USA we offer the contests with the biggest prizes
            </div>
            <div class="dp-xs-4">
                <img src="https://d2tjpz01y5bfgl.cloudfront.net/lp/UK/DTI/phone-icon.png" alt="">
                <span>FOLLOW THE ACTION</span>
                Download the App (available on iOS and Android) and see your progress in real time
            </div>
        </div>
    </div>
</div>

<!-- 회원가입 -->
<form>
<div class="modal fade" id="joinMember" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">SIGN UP</h4>
            </div>
            <div id="chk_div">            
                <div class="modal-body">               
                    <input type="hidden" id="code" value="$code">
                    <div class="form-group">
                        <label>User ID (e-mail)</label>
                        <input type="email" id="id" class="form-control" placeholder="ID">
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label>Passwords</label>
                        <input type="password" id="pw"  class="form-control" placeholder="Passwords">
                    </div>
                    <div class="form-group">
                        <label>Confirm</label>
                        <input type="password" id="co_pw"  class="form-control" placeholder="Confirm Passwords">
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <p>
                            <select id="timezone" class="form-control" title="Select Time Zone" name="timezone" data-width="150px">
                                <option value="1">Asia/Bangkok</option>                    
                                <option value="2">Asia/Seoul</option>                    
                            </select>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="bankname">Date of Birth</label>
                        <div style="clear: both"></div>
                        <div class="dp-xs-2 dp_margin_right10">
                            <input class="form-control" type="text" id="day" placeholder="Day">
                        </div>
                        <div class="dp-xs-2 dp_margin_right10">
                            <input class="form-control" type="text" id="month" placeholder="Month">
                        </div>
                        <div class="dp-xs-2">
                            <input class="form-control" type="text" id="year" placeholder="Year" >
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div class="checkbox">
                        <label>
                            <input name="chkbox" type="checkbox">  
                            <span class="dp_font_12px">I agree to the Terms of Use and Privacy Policy and confirm that I am at least 18 years of age. </span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="chkbox" type="checkbox">  
                            <span class="dp_font_12px">I agree to receive email communications and offers from Fantasy Sport Club.</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                    <button type="button" class="btn btn-primary btn-join-member" onclick="join_process()">SIGN UP</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 로그인 -->
<div class="modal fade" id="logIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" >SIGN IN</h4>
            </div>
            <div class="modal-body">               
                <div class="form-group">
                    <label>Username/Email</label>
                    <input type="text" id="id_login" class="form-control" placeholder="ID">
                </div>
                <div class="form-group">
                    <label>Passwords</label>
                    <input type="password" id="pw_login" class="form-control" placeholder="Passwords">
                </div>
                <div>
                    Forgot your password?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-primary btn-login" onclick="login_process()">SIGN IN</button>
            </div>
        </div>
    </div>
</div>