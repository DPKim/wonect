<div class="step3">
    <div class="bg_content text-center padding50">
        <div class="step_title">Choose a type</div>
        <div class="section-type col-centered selected">
            <div class="col-md-1">
                <img src="<?= INC_PUBLIC ?>images/green-checkmark.png">
            </div>
            <div class="col-md-10">
                <div class="title">Public</div>
                <div class="sub">Games are posted in our lobby and available for anyone to join</div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div class="section-type col-centered notyet">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <div class="title">Private</div>
                <div class="sub">Games are invite only. Games will not be posted in our lobby</div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div class="game_bar"> 
            <hr class="style3">
        </div>
        <div class="clearfix"></div>
        <div class="section-type2 col-centered">
            <div class="head notyet">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                    <div class="title">Head To Head</div>
                </div>
                <div class="sub">Two player winnner take all contest</div>
            </div>
            <div class="multi selected">
                <div class="col-md-1">
                    <img src="<?= INC_PUBLIC ?>images/green-checkmark.png">
                </div>
                <div class="col-md-10">
                    <i class="fa fa-users fa-5x" aria-hidden="true"></i>
                    <div class="title">Multiplayer</div> 
                </div>
                <div class="sub">Three or more entrants; you set the prize structure</div>
            </div>
        </div>  
        <div class="clearfix"></div>
    </div>
    <?php
    $arr_json_data = json_decode($get_json_data, true);
    $arr_json_data['type'] = 'public';
    $arr_json_data['type2'] = 'multiplayer';

    $json_data = urlencode(json_encode($arr_json_data));
    ?>
    <div class="dp_margin_top10 text-right">
        <button type="button" class="btn btn-info btn-lg btn-back2">BACK</button>
        <button class="btn btn-primary btn-lg btn-next3" data-json="<?= $json_data ?>">NEXT</button>
    </div>
</div>