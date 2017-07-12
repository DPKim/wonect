<div class="step4">
    <div class="bg_content text-center padding50">
        <div class="step_title">Choose your entry details</div>
        <div class="section-details">
            <div class="input_detail col-centered">
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Contest Name:</div>
                    <div class="col-md-8">
                        <input id="name" class="form-control">
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div>
                    <div class="col-md-4 text-right">Size of Contest:</div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="size">
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Entry Fee:</div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="fee">
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Multi Entry:</div>
                    <div class="col-md-8">
                        <select class="form-control" id="multi">
                            <option value="1">1</option>
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="150">150</option>
                            <option value="200">200</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <p></p>
                <div class="margin_top_10">
                    <div class="col-md-4 text-right">Prize Structure:</div>
                    <div class="col-md-8">
                        <select class="form-control" id="prize">
                            <option value="0">T1</option>
                            <option value="2">T2</option>
                            <option value="3">T3</option>
                            <option value="4">T4</option>
                            <option value="5">T5</option>
                            <option value="1">50/50</option>
                            <option value="6">Multi</option>
                            <option value="7">X2</option>
                            <option value="8">X3</option>
                            <option value="9">X10</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <button class="btn btn-default btn_peview">미리보기</button>
        <div id="preview" class="section-details" style="margin-top: 10px">
            preview
        </div>
    </div>

    <div class="dp_margin_top10 text-right">
        <div id="chk_preview">
            <button type="button" class="btn btn-info btn-lg btn-back2">BACK</button>
            <button class="btn btn-primary btn-lg btn-next4" data-json="<?= $get_val ?>">NEXT</button>
        </div>
    </div>
</div>