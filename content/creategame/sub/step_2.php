<div class="step2">
    <div class="bg_content text-center padding50">
        <div class="step_title">Choose a start time</div>
        <ul class="section-time">
            <?= $li_date ?>
            <?= $db_game->$stepFunc()['li_game'] ?>
        </ul>
        <div class="clearfix"></div>
        <div class="game_bar"> 
            <span class="select_sub">game</span>
            <hr class="style3">
            <div class="col-md-12">
                <?= $section_daily ?>
                <?= $db_game->$stepFunc()['daily_game'] ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="dp_margin_top10 text-right">
        <button type="button" class="btn btn-info btn-lg btn-back2">BACK</button>
        <button class="btn btn-primary btn-lg btn-next2">NEXT</button>
    </div>
</div>