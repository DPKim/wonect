<div class="step5">
    <div class="bg_content text-center padding50">
        <?php
        // 게임 카테고리 가져오기 
        $qry_getCategory = "select * from game_category where gc_idx = {$get_cate}";
        $result_getCategory = mysqli_query($conn, $qry_getCategory);
        $arr_getCategory = mysqli_fetch_array($result_getCategory);
        ?>
        <div class="step_title">Confirm your entry</div>
        <div class="section-details">
            <div class="input_detail col-centered">
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Sport:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail"><?= $arr_getCategory[1] ?></span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Title:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail"><?= str_replace("*w*w", "'", $arr_json_data['name']) ?></span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Time:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail">
                            <?= $arr_json_data['week'] ?> 
                            <?= $arr_json_data['day'] ?> 
                            <?= $arr_json_data['mon'] ?> 
                            <?= $arr_json_data['hour'] ?>:<?= $arr_json_data['min'] ?>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Type:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail">
                            <?= strtoupper($arr_json_data['type']) ?> <?= strtoupper($arr_json_data['type2']) ?>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Size:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail">
                            <?= $arr_json_data['size'] ?> Entry
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Fee:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail"><?= strtoupper($arr_json_data['fee']) ?> G</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Multi:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail">
                            <?= $arr_json_data['multi'] ?>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Prize Structure:</div>
                    <div class="col-md-8 text-left">
                        <span class="finish_detail">
                            <?= prize_type($arr_json_data['prize']) ?>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>

            </div>
        </div>
        <span>
            By creating this contest I understand that 
            <span>$5</span>
            will be debited from my account. 
            I understand that I am reserving my entry into this contest without a lineup. 
            If the contest runs before I draft and submit my lineup, I will lose my entry fee and the contest. 
            If the contest does not fill it will not run and all entry fees will be refunded. 
        </span>
    </div>
    <div class="dp_margin_top10 text-right">
        <button type="button" class="btn btn-info btn-lg btn-back2">BACK</button>
        <button class="btn btn-warning btn-lg btn-next5" data-json="<?= $get_val ?>">FINISH</button>
    </div>
</div>