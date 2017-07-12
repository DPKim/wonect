<?php

class DraftGame extends DB_conn {

    public $g_idx;
    public $lu_idx;
    public $conn;
    public $conn_game;
    public $fee;
    public $arr = array();
    public $total_prize;
    public $daily = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    public $g_date;
    public $start_time;
    public $start_date;
    public $is_edit = false;
    public $what_category;
    public $category_arr = array();
    public $category_count;
    public $lineup_arr = array();
    public $remSalary;
    public $lineup_tb;
    //
    public $temp_count = 0;
    public $temp_double_header = array();

    // CONSTRUCT ////////////////
    function __construct($idx, $type, $lu_idx, $u_idx) {
        $this->conn = $this->dbconnect();
        $this->conn_game = $this->dbconnect_game();

        // 현재 엔트리에 이상 없는지 체크할 것
        $qry_chk_entry = "select g_size, g_entry, g_multi_max, count(c.jc_u_idx) as count, g_fee, ";
        $qry_chk_entry .= "( select m_deposit from members where m_idx = $u_idx ) as deposit ";
        $qry_chk_entry .= "from game ";
        $qry_chk_entry .= "left join ( ";
        $qry_chk_entry .="select jc_idx, jc_game, jc_u_idx from join_contest ";
        $qry_chk_entry .="where jc_u_idx = $u_idx ";
        $qry_chk_entry .=") as c on c.jc_game = g_idx ";
        $qry_chk_entry .="where g_idx = $idx";

        $result_chk_entry = mysqli_query($this->conn, $qry_chk_entry);
        $arr_chk_entry = mysqli_fetch_assoc($result_chk_entry);

        $this->set_g_idx($idx);
        $this->set_arr($idx);

        $this->set_g_date($this->arr['g_date']);
        $this->what_category = strtoupper($this->arr['gc_name']);
        $this->set_category_arr();
        $this->category_count = count($this->category_arr);
        //
        if ($type == 1) {

            // 라인업 정보 가져오기
            if (isset($lu_idx)) {
                $this->set_lu_idx($lu_idx);
                $this->is_edit = true;
                $this->set_lineup_arr($lu_idx);
            } else {
                $this->is_edit = false;
            }
        }

        if ($this->is_edit == false) {
            $count_size = $arr_chk_entry['g_size'];
            $count_entry = $arr_chk_entry['g_entry'];

            if ($arr_chk_entry['deposit'] < $arr_chk_entry['g_fee']) {
                echo "<script>alert('입장을 위한 Deposit이 부족합니다.');location.replace('index.php?menu=store');</script>";
                return false;
            }

            if ($count_entry >= $count_size) {
                echo "<script>alert('현재 입장 가능 인원이 모두 찼습니다.');location.replace('index.php?menu=lobby');</script>";
                return false;
            }

            if ($arr_chk_entry['count'] >= $arr_chk_entry['g_multi_max']) {
                echo "<script>alert('입장 가능 횟수를 초과하였습니다.');location.replace('index.php?menu=lobby');</script>";
                return false;
            }
        }

        $this->make_lineup();
    }

    // GETER ////////////////////
    function get_g_idx() {
        return $this->g_idx;
    }

    function get_lu_idx() {
        return $this->lu_idx;
    }

    function get_fee() {
        return number_format($this->arr['g_fee']);
    }

    function get_total_prize() {
        return number_format($this->arr['g_fee'] * $this->arr['g_size'] - ($this->arr['g_fee'] * $this->arr['g_size'] * 0.15));
    }

    function get_times() {
        $g_year = $this->g_date->format("Y");
        $g_mon = $this->g_date->format("m");
        $g_week = $this->daily[$this->g_date->format("w")];
        $g_day = $this->g_date->format("d");
        $g_hour = $this->g_date->format("H");
        $g_min = $this->g_date->format("i");
        return array(
            'year' => $g_year,
            'mon' => $g_mon,
            'week' => $g_week,
            'day' => $g_day,
            'hour' => $g_hour,
            'min' => $g_min
        );
    }

    function get_start_time() {
        $g_hour = $this->g_date->format("H");
        $g_min = $this->g_date->format("i");
        return $this->start_time = "$g_hour:$g_min:00";
    }

    function get_start_date() {
        $g_year = $this->g_date->format("Y");
        $g_mon = $this->g_date->format("m");
        $g_day = $this->g_date->format("d");
        return $this->start_date = "$g_year-$g_mon-$g_day ";
    }

    // SETER ////////////////////
    function set_g_idx($idx) {
        return $this->g_idx = $idx;
    }

    function set_lu_idx($idx) {
        return $this->lu_idx = $idx;
    }

    function set_arr($idx) {
        $qry_get_game = "select * from game ";
        $qry_get_game .= "left join game_category on gc_idx = g_sport ";
        $qry_get_game .= "where g_idx = $idx ";
        $result_get_game = mysqli_query($this->conn, $qry_get_game);
        $arr_get_game = mysqli_fetch_assoc($result_get_game);

        return $this->arr = $arr_get_game;
    }

    function set_g_date($datetime) {
        return $this->g_date = new DateTime($datetime);
    }

    function set_category_arr() {
        $arr = json_decode($this->arr['gc_pos'], true);
        return $this->category_arr = $arr['pos'];
    }

    function set_lineup_arr($lu_idx) {
        $qry = "select * from lineups_history where lu_idx = $lu_idx";
        $result = mysqli_query($this->conn, $qry);
        $count = mysqli_num_rows($result);
        //
        for ($i = 0; $i < $count; $i++) {
            $arr = mysqli_fetch_assoc($result);
            array_push($this->lineup_arr,$arr);
        }
    }

}

class Function_Draftgame extends DraftGame {

    function make_cate() {
        $unique = array_unique($this->category_arr);
        foreach ($unique as $value) {
            $tab_arr .= "<li class='sort' data-sort='$value'>$value</li>";
        }
        return $tab_arr;
    }

    function make_lineup() {
        $remSalary = 50000;
        //
        for ($i = 0; $i < $this->category_count; $i++) {
            if ($this->is_edit == false) {
                $tr_arr .=<<< TR
                    <tr class ="lineup_{$this->category_arr[$i]}">
                        <td style="width:10%">{$this->category_arr[$i]}</td>
                        <td style="width:30%" class="name"></td>
                        <td style="width:30%" class="team"></td>
                        <td style="width:25%" class="salary"></td>
                        <td style="width:5%" class="del"></td>
                    </tr>
TR;
            } else {
                $player = $this->get_DB_player($this->lineup_arr[$i]['player_id']);
                $salary = '$' . numberFormat_for_float($player['player_salary']);
                $remSalary -= $player['player_salary'];

                switch ($this->what_category) {
                    case 'MLB':
                        $name = $player['player_first_name'] . ' ' . $player['player_last_name'];
                        break;
                    case 'LOL':
                        $name = $player['player_nickname'];
                        break;
                }

                $tr_arr .=<<< TR
                    <tr class ="lineup_{$this->category_arr[$i]}">
                        <td style="width:10%">{$this->category_arr[$i]}</td>
                        <td style="width:30%" class="name">
                           $name
                        </td>
                        <td style="width:30%" class="team">
                           {$player['team_abbr']}
                        </td>
                        <td style="width:25%" class="salary">
                           $salary
                        </td>
                        <td style="width:5%" class="del">
                           <img class="del-player" src="public/images/minus.png" data-game="{$this->lineup_arr[$i]['game_id']}" data-del-index="{$this->lineup_arr[$i]['player_id']}" onclick="delPlayer('{$this->lineup_arr[$i]['player_id']}')" style="cursor: pointer;">
                        </td>
                    </tr>
TR;
            }
        }
        $this->remSalary = numberFormat_for_float($remSalary);
        $this->lineup_tb = $tr_arr;
    }

    function make_name() {
        if ($this->arr['g_name']) {
            $title = "-{$this->arr['g_name']}-";
        } else {
            $title = '-Untitled-';
        }
        return "{$this->arr['gc_name']}{$title}[ {$this->arr['g_size']} Entry MAX, Total prizes \${$this->get_total_prize()} ]";
    }

    function make_btn() {
        if ($this->is_edit == false) {
            $fee = numberFormat_for_float($this->arr['g_fee']);
            $btn = <<< BTN
                <button class="btn btn-primary btn-lg btn-confrim-draft" data-coin='{$this->arr['g_fee']}' data-category= '{$this->arr['g_sport']}' data-game = '{$this->arr['g_idx']}'>
                    <span class="btn-draft">ENTER | $ $fee</span>
                </button>
BTN;
        } else {
            $btn = <<< BTN
                <button class="btn btn-primary btn-lg btn-edit-draft" data-index = '{$this->lu_idx}'>
                    <span class="btn-draft">EDIT</span>
                </button>
BTN;
        }
        return $btn;
    }

    function get_DB_player($id) {
        $cate = strtolower($this->what_category);

        $qry = "select * from {$cate}_team_profile_player ";
        $qry .= "where player_id = '$id' ";
        $result = mysqli_query($this->conn_game, $qry);
        $arr = mysqli_fetch_assoc($result);
        return $arr;
    }

    function get_player() {
        $json_game = $this->arr['g_json'];
        $arr_team = json_decode($json_game, true);
        $count_team = count($arr_team);

        $cate = strtolower($this->what_category);

        $team_players = array();
        $k = 0;
        for ($i = 0; $i < $count_team; $i++) {

            // 홈팀 선수 넣기
            $qry_home_player = "select * from {$cate}_team_profile_player ";
            $qry_home_player .= "where team_id = '{$arr_team[$i]['home_id']}' ";
            //
            $result_home_player = mysqli_query($this->conn_game, $qry_home_player);
            $count_home_player = mysqli_num_rows($result_home_player);

            for ($j = 0; $j < $count_home_player; $j++) {
                $arr_home_player = mysqli_fetch_assoc($result_home_player);
                $team_players[$k]['game_id'] = $arr_team[$i]['game_id'];
                $team_players[$k]['player_id'] = $arr_home_player;
                $k++;
            }

            // 원정팀 선수 넣기
            $qry_away_player = "select * from {$cate}_team_profile_player ";
            $qry_away_player .= "where team_id = '{$arr_team[$i]['away_id']}' ";
            $result_away_player = mysqli_query($this->conn_game, $qry_away_player);
            $count_away_player = mysqli_num_rows($result_away_player);

            for ($j = 0; $j < $count_away_player; $j++) {
                $arr_away_player = mysqli_fetch_assoc($result_away_player);
                $team_players[$k]['game_id'] = $arr_team[$i]['game_id'];
                $team_players[$k]['player_id'] = $arr_away_player;
                $k++;
            }
        }
        return $team_players;
    }

    // 사용하지 않는 포지션 묶음
    function chk_multi_position($position) {
        switch ($this->what_category) {
            case 'MLB':
                if ($position == 'RP' || $position == 'SP') {
                    return $position = 'P';
                } else if ($position == 'LF' || $position == 'CF' || $position == 'RF') {
                    return $position = 'OF';
                } else if ($position == 'DH') {
                    return false;
                } else {
                    return $position;
                }
        }
    }

    function make_table() {
        $arr = $this->get_player();
        $count_table = count($arr);
        $url_img = INC_PUBLIC;

        $table = array();
        for ($i = 0; $i < $count_table; $i++) {
            $point = round($arr[$i]['player_id']['player_point']);
            $salary = $arr[$i]['player_id']['player_salary'];

            // 종목에 따른 포지션 분기 처리
            switch ($this->what_category) {
                case 'MLB':
                    $p_position = $arr[$i]['player_id']['player_primary_position'];
                    $position = $this->chk_multi_position($p_position);
                    if ($position == false) {
                        continue;
                    }

                    // 작은 따옴표 처리할것
                    $t_name = str_replace("'", "#", $arr[$i]['player_id']['team_abbr']);
                    $p_f_name = str_replace("'", "#", $arr[$i]['player_id']['player_first_name']);
                    $p_l_name = str_replace("'", "#", $arr[$i]['player_id']['player_last_name']);
                    break;
                //
                case 'LOL':
                    $position = $arr[$i]['player_id']['player_primary_position'];
                    // 작은 따옴표 처리할것
                    $t_name = str_replace("'", "#", $arr[$i]['player_id']['team_abbr']);
                    $p_f_name = str_replace("'", "#", $arr[$i]['player_id']['player_full_name']);
                    $p_l_name = str_replace("'", "#", $arr[$i]['player_id']['player_nickname']);
                    break;
            }


            $table[$i] = array(
                $position,
                $position,
                $p_f_name,
                $p_l_name,
                $t_name,
                $point,
                $salary,
                $position,
                $arr[$i]['game_id'],
                $arr[$i]['player_id']['idx'],
                $arr[$i]['player_id']['player_salary'],
                $p_f_name,
                $p_l_name,
                $t_name,
                $arr[$i]['player_id']['player_id'],
                strtolower($this->what_category)
            );
        }
        return $table;
    }

}
