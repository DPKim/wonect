<?php

class Contest extends DB_conn {

    public $u_idx;
    public $category;
    public $category_name;
    public $click_page;
    public $status;
    ////
    public $limit_row;
    public $limit_navi;
    public $limit_qry;

    // CONSTRUCT ////////////////
    function __construct($idx, $category, $click_page, $limit_row, $limit_navi, $status) {
        $this->dbconnect();
        $this->dbconnect_game();
        //
        $this->u_idx = $idx;
        $this->category = $category;
        $this->click_page = $click_page;

        if ($status == 0 || $status == 1) {
            $this->status = ' and (g_status =0 or g_status = 1) ';
        } else if ($status == 2) {
            $this->status = ' and g_status = 2 ';
        } else if ($status == 3) {
            $this->status = ' and g_status = 3 ';
        }

        $this->limit_row = $limit_row;
        $this->limit_navi = $limit_navi;
        $this->selectDBLimit();
        $this->selectDB();
        $this->get_amount_winning();
        //
        if (isset($_SESSION['LOCALE']['locale'])) {
            $locale = $_SESSION['LOCALE']['locale'];
        } else {
            $locale = 'Etc/GMT';
        }
        $this->setTimezone($locale);
    }

    // 쿼리 리미트 함수
    function selectDBLimit() {
        $limit_start = $this->click_page * $this->limit_row;
        $qry = "limit $limit_start, {$this->limit_row}";

        return $this->limit_qry = $qry;
    }

    // DB 가져오기
    function selectDB() {
        $category = $this->category;

        if ($category == 0) {
            $and = " ";
        } else {
            $and = "and lu_gc_idx = $category ";
        }
        $qry = 'select join_contest.*,game.*,  game_category.gc_name ';
        $qry .= 'from ( ';
        $qry .= '       select jc_idx from join_contest	';
        $qry .= '       where jc_u_idx =' . $this->u_idx;
        $qry .= ' ) b join join_contest on join_contest.jc_idx = b.jc_idx ';
        $qry .= 'left join lineups on lu_idx = jc_lineups ';
        $qry .= 'left join game on g_idx = jc_game ';
        $qry .= 'left join game_category on gc_idx = g_sport ';
        $qry .= 'left join members on m_idx = lu_u_idx ';
        $qry .= 'where lu_u_idx = ' . $this->u_idx;
        $qry .= $this->status;
        $qry .= $and;
        $qry .= 'group by jc_game ';
        $qry .= 'order by g_date desc, jc_result desc ';
        $qry .= $this->limit_qry;        

        $result = mysqli_query($this->conn, $qry);
        if ($result) {
            $count = mysqli_num_rows($result);
            $this->result = $result;
            $this->count = $count;
            $this->qry = $qry;
        } else {
            echo mysqli_error($this->conn);
            die;
        }
    }

    // 전체 DB 갯수
    function selectDB_total() {
        $category = $this->category;

        if ($category == 0) {
            $where = ' ';
        } else {
            $where = "and g_sport = $category ";
        }
        $qry = "select * from join_contest ";
        $qry .= "left join game on g_idx = jc_game ";
        $qry .= "where jc_u_idx = $this->u_idx ";
        $qry .= $this->status;
        $qry .= $where;

        $result['qry'] = $qry;
        $result['result'] = mysqli_query($this->conn, $qry);
        $result['count'] = mysqli_num_rows($result['result']);

        return $result;
    }

}

class Contest_upcoming extends Contest {

    public $total_salary = 0;
    public $total_score = 0;
    public $result;
    public $count;
    public $qry;
    public $posible_winning;
    // 
    public $winnerPrize;

    // 카테고리에 맞는 정보 가져 올 것 
    function selectContest() {

        if ($this->count > 0) {
            for ($i = 0; $i < $this->count; $i++) {
                $arr = $this->fetchDB_assoc($this->result);
                $contest[$i] = $this->proccess($arr);
            }
        } else {
            $contest[0] = '<div class="alert alert-info" role="alert" style="margin-bottom:0px">There is no Data for this game.</div>';
        }
        return $contest;
    }

    // 총 기대 상금 계산
    function get_amount_winning() {
        $db = $this->selectDB_total();
        for ($i = 0; $i < $db['count']; $i++) {
            $arr = $this->fetchDB_assoc($db['result']);
            //
            $class = new RankReward($arr['g_size'], $arr['g_fee'], $arr['g_prize']);
            $total = $class->total_reward;
            $reward = $class->make_rank_arr();
            //   
            $topPrize = $reward[0]['reward'];
            $this->posible_winning += $topPrize;
        }
    }

    // 처리하기
    function proccess($arr) {

        $reward = new RankReward($arr['g_size'], $arr['g_fee'], $arr['g_prize']);
        //
        $total_prize = $reward->total_reward;
        $topPrize = numberFormat_for_float($reward->make_rank_arr()[0]['reward']);
        //

        $this->category_name = $this->getCategoryDB($arr['g_sport'])[0]['gc_name'];
        $game_name = makeName($this->category_name, $arr['g_name'], $arr['g_size'], $total_prize);
        $title = $game_name;
        $placesPaid = numberFormat_for_float($total_prize);

        // 시간대에 맞는 날짜로 변경
        $locale = $this->locale;
        $newdate = $this->change_timezone($arr['g_date'], $arr['g_timezone'], $locale);

        $livein = $newdate['day'];
        $fee = numberFormat_for_float($arr['g_fee']);
        $public_entries = numberFormat_for_float($arr['g_entry']);
        $idx = $arr['g_idx'];

        return $this->set_tr_upcoming($title, $livein, $placesPaid, $total_prize, $public_entries, $fee, $topPrize, $idx);
    }

    // upcoming tr
    function set_tr_upcoming($title, $livein, $placesPaid, $total_prize, $public_entries, $fee, $topPrize, $idx) {
        $tr = <<< TR
                <tr>
                    <td style="width:20%;" class='content_BBS' data-idx= "$idx">
                        <div class="title_live" style="padding-left:20px">
                            <span class="contest_name title_marquee">$title</span>
                        </div>
                    </td>
                    <td style="width:8%">
                        <button class="btn btn-info btn-xs btn_contest_edit" data-index="$idx">
                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>Edit
                        </button>
                    </td>
                    <td style="width:8%">
                        <button class="btn btn-default btn-xs notyet">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>Invite
                        </button>
                    </td>
                    <td style="width:8%">
                        <button class="btn btn-default btn-xs btn_add_contest" data-index="$idx">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add
                        </button>
                    </td>
                    <td style="width:16%" class="count_down">$livein</td>
                    <td style="width:8%">$placesPaid G</td>
                    <td style="width:8%">$total_prize G</td>
                    <td style="width:8%">$public_entries</td>
                    <td style="width:8%">$fee G</td>
                    <td style="width:8%">$topPrize G</td>
                </tr>
TR;
        return $tr;
    }

}

class Contest_live extends Contest {

    public $total_salary = 0;
    public $total_score = 0;
    public $result;
    public $count;
    public $qry;
    public $posible_winning;
    // 
    public $winnerPrize;

    // 카테고리에 맞는 정보 가져 올 것 
    function selectContest() {
        if ($this->count > 0) {
            for ($i = 0; $i < $this->count; $i++) {
                $arr = $this->fetchDB_assoc($this->result);
                $contest[$i] = $this->proccess($arr);
            }
        } else {
            $contest[0] = '<div class="alert alert-info" role="alert" style="margin-bottom:0px">There is no Data for this game.</div>';
        }
        return $contest;
    }

    // 총 기대 상금 계산
    function get_amount_winning() {
        $db = $this->selectDB_total();
        for ($i = 0; $i < $db['count']; $i++) {
            $arr = $this->fetchDB_assoc($db['result']);
            //
            $class = new RankReward($arr['g_size'], $arr['g_fee'], $arr['g_prize']);
            $reward = $class->make_rank_arr();
            //   
            $topPrize = $reward[0]['reward'];
            $this->posible_winning += $topPrize;
        }
    }

    // 처리하기
    function proccess($arr) {

        $reward = new RankReward($arr['g_size'], $arr['g_fee'], $arr['g_prize']);
        //
        $total_prize = $reward->total_reward;
        $topPrize = numberFormat_for_float($reward->make_rank_arr()[0]['reward']);
        //

        $this->category_name = $this->getCategoryDB($arr['g_sport'])[0]['gc_name'];
        $game_name = makeName($this->category_name, $arr['g_name'], $arr['g_size'], $total_prize);
        $title = $game_name;
        $fee = numberFormat_for_float($arr['g_fee']);
        $public_entries = numberFormat_for_float($arr['g_entry']);
        $idx = $arr['g_idx'];

        // 실제 라이브 데이터 연동 후 구현
        $winning = 0;
        $myScore = 0;
        $topScore = 0;
        $myRank = 0;

        return $this->set_tr_live($title, $public_entries, $winning, $myScore, $topScore, $myRank, $fee, $topPrize, $idx);
    }

    // upcoming tr
    function set_tr_live($title, $public_entries, $winning, $myScore, $topScore, $myRank, $fee, $topPrize, $idx) {
        $tr = <<< TR
                <tr>
                    <td style="width:20%;" class='content_BBS' data-idx= "$idx">
                        <div class="title_live">
                            <span class="contest_name title_marquee">$title</span>
                        </div>
                    </td>
                    <td style="width:8%;">
                        <button class="btn btn-default btn-xs notyet">
                             <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>LIVE
                        </button>
                    </td>
                    <td style="width:9%">$public_entries</td>
                    <td style="width:9%">$winning</td>
                    <td style="width:9%">$myScore</td>
                    <td style="width:9%">$topScore</td>
                    <td style="width:9%">$myRank</td>
                    <td style="width:9%">$fee G</td>
                    <td style="width:9%">$topPrize G</td>
                </tr>
TR;
        return $tr;
    }

}

class Contest_finish extends Contest {

    public $total_salary = 0;
    public $total_score = 0;
    public $result;
    public $count;
    public $qry;
    public $posible_winning;
    // 
    public $winnerPrize;

    // 카테고리에 맞는 정보 가져 올 것 
    function selectContest() {
        if ($this->count > 0) {
            for ($i = 0; $i < $this->count; $i++) {
                $arr = $this->fetchDB_assoc($this->result);
                $contest[$i] = $this->proccess($arr);
            }
        } else {
            $contest[0] = '<div class="alert alert-info" role="alert" style="margin-bottom:0px">There is no Data for this game.</div>';
        }
        return $contest;
    }

    // 총 기대 상금 계산
    function get_amount_winning() {
        $db = $this->selectDB_total();
        for ($i = 0; $i < $db['count']; $i++) {
            $arr = $this->fetchDB_assoc($db['result']);
            $topPrize = checkPrize($arr['g_prize'], $arr['g_fee'], $arr['g_size'])[1];
            $this->posible_winning += $topPrize;
        }
    }

    // 처리하기
    function proccess($arr) {

        $reward = new RankReward($arr['g_size'], $arr['g_fee'], $arr['g_prize']);
        $total_prize = $reward->total_reward;
        $topPrize = numberFormat_for_float($reward->make_rank_arr()[0]['reward']);
        //
        $this->category_name = $this->getCategoryDB($arr['g_sport'])[0]['gc_name'];
        $game_name = makeName($this->category_name, $arr['g_name'], $arr['g_size'], $total_prize);
        $title = $game_name;
        $public_entries = numberFormat_for_float($arr['g_entry']);
        $idx = $arr['g_idx'];
        $won = $this->calWon($idx);
        $result = numberFormat_for_float($arr['jc_result']);
        $completed = $this->get_completed($arr['lu_json']);

        return $this->set_tr_finish($title, $public_entries, $won, $result, $completed, numberFormat_for_float($total_prize), $topPrize, $public_entries, $idx);
    }

    // 완료 날자 가져오기
    function get_completed($lu_json) {
        $arr = json_decode($lu_json, true);
        $cate_name = strtolower($this->category_name);
        //
        $old_date = '';
        foreach ($arr as $value) {
            $g_id = $value['game_id'];
            //
            $qry = "select game_final_time from {$cate_name}_game_daily_schedule ";
            $qry .= "where games_id = '$g_id' ";
            $result = mysqli_query($this->conn_game, $qry);
            if ($result) {
                $date = mysqli_fetch_array($result)[0];
                if ($old_date < $date) {
                    $old_date = $date;
                }
            }
        }

        $new_date = $this->change_timezone($old_date, 'GMT', $this->locale);
        return $new_date['day'];
    }

    // 우승 상금 계산하기
    function calWon($g_idx) {

        $qry = "select sum(pg_amount) from history_push_gold ";
        $qry .= "where pg_g_idx = $g_idx ";
        $qry .= "and pg_u_idx = $this->u_idx ";
        $qry .= "and pg_comment = 'contest prize' ";
        $result = mysqli_query($this->conn, $qry);

        if ($result) {
            $arr = mysqli_fetch_array($result);
            if ($arr[0] !== null) {
                $won = numberFormat_for_float($arr[0]);
            } else {
                $won = 0;
            }
            return $won;
        } else {
            return false;
        }
    }

    // upcoming tr
    function set_tr_finish($title, $public_entries, $won, $result, $completed, $total_prize, $top_prize, $places_paid, $idx) {

        $tr = <<< TR
                <tr>
                    <td style="width:22%;" class='content_BBS' data-idx= "$idx">
                        <div class="title_live" style="padding-left:20px">
                            <span class="contest_name title_marquee">$title</span>
                        </div>
                    </td>
                    <td style="width:8%;">
                        <button class="btn btn-primary btn-xs btn_contest_result" data-index= "$idx">
                             <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Result
                        </button>
                    </td>
                    <td style="width:8%">$public_entries</td>
                    <td style="width:7%">$won G</td>
                    <td style="width:10%">$result</td>
                    <td style="width:15%">$completed</td>
                    <td style="width:10%">$total_prize G</td>
                    <td style="width:10%">$top_prize G</td>
                    <td style="width:10%">$places_paid</td>
                </tr>
TR;
        return $tr;
    }

}
