<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

class Lineups extends DB_conn {

    public $u_idx;
    public $category;
    public $click_page;
    public $conn;
    public $conn_game;
    ////
    public $limit_row;
    public $limit_navi;
    //temp
    public $total_salary = 0;

    // CONSTRUCT ////////////////
    function __construct($idx, $category, $click_page, $limit_row, $limit_navi) {
        $this->conn = $this->dbconnect();
        $this->conn_game = $this->dbconnect_game();
        ///
        $this->u_idx = $idx;
        $this->category = $category;
        $this->click_page = $click_page;
        $this->limit_row = $limit_row;
        $this->limit_navi = $limit_navi;
    }

    // 쿼리 리미트 함수
    function selectDBLimit() {
        $limit_start = $this->click_page * $this->limit_row;
        $qry = "limit $limit_start, {$this->limit_row}";

        return $qry;
    }

    // 라인업 전체함수
    function selectDB_lineup_total() {
        $u_idx = $this->u_idx;
        $category = $this->category;

        if ($category == 0) {
            $where = ' ';
        } else {
            $where = "and lu_gc_idx = $category ";
        }
        $qry = "select * from lineups ";
        $qry .= "where lu_u_idx = {$u_idx} {$where}";

        $result['result'] = mysqli_query($this->conn, $qry);
        $result['count'] = mysqli_num_rows($result['result']);

        return $result;
    }

    // DB 쿼리 함수
    function selectDB_lineup($g_idx) {
        $u_idx = $this->u_idx;
        $category = $this->category;
        $qry_limit = $this->selectDBLimit();

        if ($category == 0) {
            $and = " ";
        } else {
            $and = "and lu_gc_idx = $category ";
        }

        if (isset($g_idx)) {
            $where_gidx = "and lu_g_idx = $g_idx ";
        }

        $qry = "select * from lineups ";
        $qry .= "where lu_u_idx = {$u_idx} {$and}";
        $qry .= $where_gidx;
        $qry .= 'order by lu_idx desc ';
        $qry .= $qry_limit;

        $result['result'] = mysqli_query($this->conn, $qry);
        $result['count'] = mysqli_num_rows($result['result']);
        return $result;
    }

    function selectDB_game($g_idx) {

        $qry = "select * from game ";
        $qry .= "where g_idx = $g_idx ";
        $result = mysqli_query($this->conn, $qry);
        return $result;
    }

    function selectDB_player($cate, $p_id) {
        $lowcase_cate = strtolower($cate);

        $qry = "select * from {$lowcase_cate}_team_profile_player ";
        $qry .= "where player_id = '{$p_id}'";
        $result = mysqli_query($this->conn_game, $qry);
        return $result;
    }

    function get_player_result($lu_idx) {
        $qry = "select * from join_contest ";
        $qry .= "where jc_lineups = '{$lu_idx}' ";
        $result = mysqli_query($this->conn, $qry);
        $score = $this->fetchDB_assoc($result)['jc_result'];
        if ($score) {
            return $score;
        } else {
            return 0;
        }
    }

    // 카테고리에 맞는 라인업 정보 가져 올 것 
    function selectLineups($g_idx) {
        $result = $this->selectDB_lineup($g_idx);

        if ($result['count'] > 0) {
            for ($i = 0; $i < $result['count']; $i++) {
                $arr = $this->fetchDB_assoc($result['result']);
                $this_category = $this->getCategoryDB($arr['lu_gc_idx'])[0];
                //
                $arr_player = $this->get_lineup_history($arr['lu_idx']);
                $player[$i] = $this->getPlayerInfo($this_category['gc_name'], $arr_player, $arr['lu_g_idx'], $arr['lu_gc_idx']);
            }
        } else {
            $player[0] = '<div class="alert alert-info" role="alert" style="margin-bottom:0px">There are no existing line-ups for this game. </div>';
        }
        return $player;
    }

    // 새로 추가된 lineup history
    function get_lineup_history($lu_idx) {
        $qry = "select * from lineups ";
        $qry .= "left join game on g_idx = lu_g_idx ";
        $qry .= "left join lineups_history on lineups_history.lu_idx = lineups.lu_idx ";
        $qry .= "where lineups_history.lu_idx = $lu_idx ";
        $result = mysqli_query($this->conn, $qry);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    // 선수 데이터 가져오기
    function getPlayerInfo($cate, $result_qry, $g_idx, $lu_gc_idx) {
        $lowcase_cate = strtolower($cate);
        $count = mysqli_num_rows($result_qry);
        //
        $game_info_result = $this->fetchDB_assoc($this->selectDB_game($g_idx));
        $lineup_info = $this->fetchDB_assoc($this->selectDB_lineup($g_idx)['result']);
        
        for ($i = 0; $i < $count; $i++) {
            
            $arr_lu = mysqli_fetch_assoc($result_qry);
            $result = $this->selectDB_player($lowcase_cate, $arr_lu['player_id']);

            if ($result) {
                $arr = $this->fetchDB_assoc($result);

                if ($i == 0) {
                    $score = $this->get_player_result($lineup_info['lu_idx']);
                    $result_score = numberFormat_for_float($score);
                }

                //선수 리스트 만들기              
                $lineupTr .= $this->set_table_lineup($arr, $lowcase_cate, $lu_gc_idx);
                $reward = new RankReward($game_info_result['g_size'], $game_info_result['g_fee'], $game_info_result['g_prize']);

                $condition = $game_info_result['g_status'];
                $entries = numberFormat_for_float($game_info_result['g_entry']);
                $total_prize = numberFormat_for_float($reward->total_reward);
                $last_edit = $arr_lu['reg_update'];
                $lu_idx = $lineup_info['lu_idx'];

                $div_lineup = $this->set_div_lineup($condition, $entries, $lineupTr, $result_score, $total_prize, $last_edit, $g_idx, $lu_idx, $cate);
            } else {
                return false;
            }
        }
        $this->total_salary = 0;
        $this->total_score = 0;

        return $div_lineup;
    }

    // 선수 리스트 Table 만들기
    function set_table_lineup($arr, $lowcase_cate, $lu_gc_idx) {
        $position = chg_pos($lu_gc_idx, $arr['player_primary_position']);
        $salary = number_format($arr['player_salary']);
        //
        switch ($lowcase_cate) {
            case 'mlb':
                $name = $arr['player_first_name'] . ' ' . $arr['player_last_name'];
                $team = $arr['team_abbr'];
                break;
            case 'lol':
                $name = $arr['player_nickname'];
                $team = $arr['team_abbr'];
                break;
        }
        //
        $this->total_salary += $arr['player_salary'];
        $lineupTr .= <<< PTR
                <tr style="background:#111">
                    <td style="width:15%">{$position}</td>
                    <td style="width:45%; cursor:pointer" class="player_name" data-category= "{$lowcase_cate}" data-index="{$arr['player_id']}">{$name}</td>
                    <td style="width:20%">{$team}</td>
                    <td style="width:20%">$ {$salary}</td>
                </tr>
PTR;
        return $lineupTr;
    }

    // DIV 만들기
    function set_div_lineup($condition, $entries, $lineupTr, $result_score, $total_prize, $last_edit, $g_idx, $lu_idx, $cate) {

        if ($condition == 1 || $condition == 0) {
            $btn_text = 'Edit'; //upcoming
            $upcoming = 'Upcoming  <span class="count_down"></span>';
            $is_upcoming = 'is_upcoming';
            $btn_edit = "<button class='btn btn-default btn-xs btn_edit_lineup dp_float_left' data-index='$g_idx' data-index-lineup='$lu_idx'>$btn_text</button>";
        } else if ($condition == 2) {
            $btn_edit = '';
            $upcoming = 'Live'; //live
            $is_upcoming = ' ';
        } else if ($condition == 4) {
            $btn_edit = '';
            $upcoming = 'Canceled'; //Canceled
            $is_upcoming = ' ';
        } else {
            $btn_edit = '';
            $upcoming = 'Finish'; //upcoming
            $is_upcoming = ' ';
        }

        $total_salary = numberFormat_for_float(50000 - $this->total_salary);

        $div_lineup = <<<LU
            <div class="box_lineup $is_upcoming">
                <div class="lineup_category_title">
                    <div class="title" style="color:#fff;">$cate</div>
                    <div>
                        $btn_edit
                    </div>
                    <div class="upcoming">
                        $upcoming
                    </div>
                </div>
                <div>
                    <table width=100%>
                        <tr style="background:#111" >
                            <td style="width:50%"># of Entries</td>
                            <td style="width:50%">Remain Salary</td>
                        </tr>
                    </table>
                    <table width=100%>
                        <tr style="color:#fff; background:#222">
                            <td style="width:50%">$entries</td>
                            <td style="width:50%">$ $total_salary</td>
                        </tr>        
                    </table>
                </div>
                <div style="height: 370px">
                    <table width=100%>
                        <tr style="background:#000">
                            <td style="width:15%">POS</td>
                            <td style="width:45%">PLAYER</td>
                            <td style="width:20%">TEAM</td>
                            <td style="width:20%">SALARY</td>
                        </tr>
                    </table>
                    <table width=100%>
                    $lineupTr
                    </table>
                </div>
                <div style="width:100%; position: absolute; bottom:0; background: #222">
                    <table width=100% style="border-top: 1px solid #777">
                        <tr>
                            <td style="text-align:left; padding-left:40px">
                                Result Score : <span class="dp_font_fff">$result_score</span>
                            </td>
                        </tr>        
                        <tr>
                            <td style="text-align:left; padding-left:40px">
                                Total Prize : <span class="dp_font_fff">$total_prize G</span>
                            </td>
                        </tr>        
                        <tr>
                            <td style="text-align:left; padding-left:40px">
                                LAST EDIT : <span class="dp_font_fff">$last_edit</span>
                            </td>
                        </tr>        
                    </table>
                </div>
            </div>
LU;
        return $div_lineup;
    }

}

?>