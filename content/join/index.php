<?php
if ($chk_login) {
    header('Location: index.php?menu=lobby');
}

// 타임존 가져오기
$qry = "select * from zone order by timezone asc ";
$result = mysqli_query($conn, $qry);
$count = mysqli_num_rows($result);
//
$option = ''; 
for ($i = 0; $i < $count; $i++) {
    $arr = mysqli_fetch_assoc($result);
    if($arr['timezone'] == 'Etc/GMT') {
     $otion .=<<< OP
         <option value="{$arr['zone_id']}" selected='' >{$arr['timezone']}</option>     
OP;
    } else {
        $otion .=<<< OP
         <option value="{$arr['zone_id']}">{$arr['timezone']}</option>     
OP;
    }
}
?>

<div class="container text-center dp_login">
    <a href="index.php?meun=lobby"><img src="<?= INC_PUBLIC ?>images/logo.png"></a>
    <h1>ONE-DAY FANTASY&nbsp;SPORTS</h1>
    <h2>JOIN SPO-BIT. WIN&nbsp;CASH.</h2>
    <div class="jumbotron" style="width: 700px; margin: 30px auto; color:#222;">
        <div style="text-align: left;">
            <h3 class="modal-title" style="font-weight: 500">JOIN</h3>
        </div>
        <div style="text-align: left; font-size: 20px; margin-top: 10px">               
            <div id="chk_div">            
                <div class="modal-body">               
                    <input type="hidden" id="code" value="$code">
                    <div class="form-group">
                        <label>User ID (e-mail)</label>
                        <input type="email" id="id" class="form-control" placeholder="ID">
                    </div>
                    <div class="form-group">
                        <label>Nick Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Name">
                        <span style="font-size:12px; font-weight: 100">(Alphabet, Number, !@#$%^&*)</span>
                    </div>
                    <div class="form-group">
                        <label>Passwords</label>
                        <input type="password" id="pw"  class="form-control" placeholder="Passwords">
                        <span style="font-size:12px; font-weight: 100">
                            At least 8 characters (Alphabet, Number, !@#$%^&*)
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Confirm</label>
                        <input type="password" id="co_pw"  class="form-control" placeholder="Confirm Passwords">
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <p>
                            <select id="timezone" class="form-control" title="Select Time Zone" name="timezone" data-width="150px">
                                <?=$otion?>               
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
                            <span class="dp_font_12px">I agree to receive email communications and offers from SPO-BIT.</span>
                        </label>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-lg btn-join-member" onclick="join_process()">SIGN UP</button>
            </div>
        </div>        
    </div>
    <div>
        © 2016 - 2017 Aman International Inc., All Rights Reserved
    </div>
</div>