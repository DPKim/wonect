<div class="step1">
    <div class="bg_content text-center padding50">
        <div class="step_title">Choose a sport</div>

        <ul class="section-content">
            <?= $db_game->$stepFunc()['li'] ?>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="dp_margin_top10 text-right">
        <!--<button type="button" class="btn btn-info btn-lg">BACK</button>-->
        <button class="btn btn-primary btn-lg btn-next1">NEXT</button>
    </div>
</div>