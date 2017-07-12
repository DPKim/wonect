<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

class ResultGame extends DB_conn {

    public $u_idx;
    public $jc_game;
    //
    public $rank_table;
    public $lineup_table;
    //
    public $game_info;

    function __construct($u_id, $g_id) {
        $this->u_idx = $u_id;
        $this->jc_game = $g_id;
        //
        $this->dbconnect();
        $this->dbconnect_game();

        // 결과 값 초기화
        $this->rank_table = '';
        $this->lineup_table = '';

        // 해당 게임의 게임 카테고리 초기화
        $this->set_game_cate($g_id);
    }

    // set Game cate
    function set_game_cate($idx) {
        $qry = "select * from game ";
        $qry .= "left join game_category on gc_idx = g_sport ";
        $qry .= "where g_idx = $idx ";
        $result = mysqli_query($this->conn, $qry);
        if ($result) {
            $arr = mysqli_fetch_assoc($result);
            $game['cate_name'] = $arr['gc_name'];
            $game['position'] = json_decode($arr['gc_pos'], true)['pos'];
            $game['qry'] = $qry;
            return $this->game_info = $game;
        } else {
            return $qry;
        }
    }

    // fetch function
    function fetchDB() {
        
    }

    // 순위 테이블 만들기
    function make_rank_table() {
        $qry = "select join_contest.*, game.*, members.m_name ";
        $qry .="from join_contest ";
        $qry .="left join lineups on lu_idx = jc_lineups ";
        $qry .="left join game on g_idx = jc_game ";
        $qry .="left join members on m_idx = jc_u_idx ";
        $qry .="where jc_game = $this->jc_game ";
        $qry .="order by jc_result desc";

        $result = mysqli_query($this->conn, $qry);
        $count = mysqli_num_rows($result);

        for ($i = 0; $i < $count; $i++) {
            $arr = mysqli_fetch_assoc($result);
            if ($i == 0) {
                $class_rank = new RankReward($arr['g_size'], $arr['g_fee'], $arr['g_prize']);
                // 전체 반복해야 할 회수 가져오기
                $count_rank = 0;
                foreach ($class_rank->make_rank_arr() as $value) {
                    $count_rank = $count_rank + $value['rank'];
                }
            }
            if ($i == $count_rank) {
                break;
            } else {
                $rank_name[$i] = $arr['m_name'];
            }
        }

        $init_count = 0;
        foreach ($class_rank->make_rank_arr() as $value) {
            $for_count = $init_count + $value['limit'];
            for ($i = $init_count; $i < $for_count; $i++) {
                $this->rank_table .= <<< RT
                    <tr>
                        <td>{$rank_name[$i]}</td>
                        <td>{$value['rank']}</td>
                        <td>{$value['reward']} G</td>
                    </tr>
RT;
            }
            $init_count = $for_count;
        }

        return $this->rank_table;
    }

    // 내 라인업 테이블 만들기
    function make_lineup_table() {

        $cate_name = strtolower($this->game_info['cate_name']);

        $qry = "select * from join_contest ";
        $qry .="left join lineups on lu_idx = jc_lineups ";
        $qry .="where jc_u_idx = {$this->u_idx} ";
        $qry .="and jc_game = {$this->jc_game}";
        $result = mysqli_query($this->conn, $qry);
        $count_result = mysqli_num_rows($result);

        for ($j = 0; $j < $count_result; $j++) {
            $arr = mysqli_fetch_assoc($result);
            $lu_idx = $arr['lu_idx'];

            // 각 라인업 선수에 대한 결과 값 가져오기
            $class_lineup = new Lineups($arr['lu_u_idx'], $arr['lu_gc_idx']);
            $lu_json = $class_lineup->get_lineup_history($lu_idx);
            $count = mysqli_num_rows($lu_json);
            //
            $this->lineup_table .=<<<LTB
                <tr>
                    <td class="dp_bg_111" width="5%">POS</td>
                    <td class="dp_bg_111" width="20%">PLAYER</td>
                    <td class="dp_bg_111" width="20%">GAME</td>
                    <td class="dp_bg_111" >DETAILS</td>
                    <td class="dp_bg_111" width="10%">SCORE</td>
                </tr>
LTB;
            for ($i = 0; $i < $count; $i++) {
                $arr_lu = mysqli_fetch_assoc($lu_json);
                //                        
                $game_id = $arr_lu['game_id'];
                $player_id = $arr_lu['player_id'];

//                $qry_r = "select * from {$cate_name}_team_profile_player ";
//                $qry_r .="left join {$cate_name}_game_daily_schedule on  {$cate_name}_game_daily_schedule.games_id= '$game_id' ";
//                $qry_r .="left join {$cate_name}_game_result on {$cate_name}_game_result.game_id= {$cate_name}_game_daily_schedule.games_id ";
//                $qry_r .="where {$cate_name}_team_profile_player.player_id = '$player_id'";

                $qry_r = "select * from lineups_history ";
                $qry_r .= "left join lineups_history_score on lineups_history_score.game_id = lineups_history.game_id ";
                $qry_r .= "where lineups_history.game_id = '$game_id'  ";
                $qry_r .= "and lineups_history.player_id = '$player_id'  ";
                //
                $result_r = mysqli_query($this->conn, $qry_r);
                $fetch = mysqli_fetch_assoc($result_r);
                //
//                $home_point = $fetch['game_home_runs'];
//                $away_point = $fetch['game_away_runs'];
                //
//                $pos = chg_pos($arr['lu_gc_idx'], $arr_lu['player_pos']);
                // 게임 결과
                if ($fetch['player_result_json']) {
                    $details = $this->result_game_detail($cate_name, $fetch['player_result_json'], $fetch['player_pos']);
                } else {
                    $details = '';                   
                }
                //
                $this->lineup_table .=<<<LTB
                    <tr>
                        <td>{$fetch['player_pos']}</td>
                        <td>{$fetch['player_name']} </td>
                        <td>{$fetch['home_name']} {$fetch['home_score']} : {$fetch['away_score']} {$fetch['away_name']}</td>
                        <td style="overflow: no-display; width: 100px" align="left">
                            {$details}
                        </td>
                        <td>{$fetch['game_players_points']}</td>
                    </tr>
LTB;
            }
        }
        return $this->lineup_table;
    }

    // 게임 마다 결과 값 다르게 표현하기
    function result_game_detail($cate, $json, $pos) {
        // 디테일 정보 가져오기
        $detail_json = json_decode($json, true);
        //
        switch ($cate) {
            case 'mlb':

                if ($pos == 'RP' || $pos == 'SP') {
                    $pos = 'P';
                }

                if ($pos !== 'P') {

                    $hitting = $detail_json['hitting'];
                    //

                    if ($hitting['s'] > 0) {
                        $details = "Single {$hitting['s']} / ";
                    } else {
                        $details = '';
                    }
                    if ($hitting['d'] > 0) {
                        $details .= "Double {$hitting['d']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($hitting['t'] > 0) {
                        $details .= "Triple {$hitting['t']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($hitting['hr'] > 0) {
                        $details .= "Home Run {$hitting['hr']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($hitting['rbi'] > 0) {
                        $details .= "Run Batted In {$hitting['rbi']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($hitting['total'] > 0) {
                        $details .= "Run {$hitting['total']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($hitting['bb'] > 0) {
                        $details .= "Base on Balls {$hitting['bb']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($hitting['hbp'] > 0) {
                        $details .= "Hit By Pitch {$hitting['hbp']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($hitting['stolen'] > 0) {
                        $details .= "Stolen Base {$hitting['stolen']} / ";
                    } else {
                        $details .= '';
                    }
                } else {

                    $pitching = $detail_json['pitching'];
                    //
                    $inning = $pitching['ip_1'] /3 ;
                    $amari = $pitching['ip_1']%3 ;
                    if($amari = 0) {
                        $amari = '';
                    } else {
                        $amari = '.'.$amari;
                    }
                    //

                    if ($inning > 0) {
                        $details = "Inning Pitched $inning$amari / ";
                    } else {
                        $details = '';
                    }
                    if ($pitching['ktotal'] > 0) {
                        $details .= "Strike Out {$pitching['ktotal']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($pitching['win'] > 0) {
                        $details .= "Win {$pitching['win']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($pitching['earned'] > 0) {
                        $details .= "Earned Run Allowed {$pitching['earned']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($onbase['h'] > 0) {
                        $details .= "Hit Against {$onbase['h']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($onbase['bb'] > 0) {
                        $details .= "Base on Balls Against {$onbase['bb']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($onbase['hbp'] > 0) {
                        $details .= "Hit Batsman {$onbase['hbp']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($games['complete'] > 0) {
                        $details .= "Complete Game {$games['complete']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($games['shutout'] > 0) {
                        $details .= "Shut Out {$games['shutout']} / ";
                    } else {
                        $details .= '';
                    }
                    if ($onbase['h'] == 0) {
                        $details .= "No Hitter 1 / ";
                    } else {
                        $details .= '';
                    }
                }
                return $details;

            case 'nba':
                if ($detail_json['points'] > 0) {
                    $point = "Points {$detail_json['points']} / ";
                } else {
                    $point = '';
                }
                if ($detail_json['three_points_made'] > 0) {
                    $points3 = "Made 3pt {$detail_json['three_points_made']} / ";
                } else {
                    $points3 = '';
                }
                if ($detail_json['rebounds'] > 0) {
                    $rebounds = "Rebound {$detail_json['rebounds']} / ";
                } else {
                    $rebounds = '';
                }
                if ($detail_json['assists'] > 0) {
                    $assist = "Assist {$detail_json['assists']} / ";
                } else {
                    $assist = '';
                }
                if ($detail_json['steals'] > 0) {
                    $steal = "Steal {$detail_json['steals']} / ";
                } else {
                    $steal = '';
                }
                if ($detail_json['blocks'] > 0) {
                    $block = "Block {$detail_json['blocks']} / ";
                } else {
                    $block = '';
                }
                if ($detail_json['turnovers'] > 0) {
                    $turnover = "Turnover {$detail_json['turnovers']}";
                } else {
                    $turnover = '';
                }
                return $point . $points3 . $rebounds . $assist . $steal . $block . $turnover;
        }
    }

}
