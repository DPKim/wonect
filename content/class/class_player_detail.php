<?php

class PlayerDetail extends DB_conn {

    public $p_id;
    public $category;
    //
    public $p_position;

    function __construct($p_id, $lower_cate) {
        $this->p_id = $p_id;
        $this->category = $lower_cate;
        $this->dbconnect();
        $this->dbconnect_game();
    }

    // 선수 데이터 가져오기
    function get_player_info() {
        $qry = "select * from {$this->category}_team_profile_player ";
        $qry .= "left join {$this->category}_team_profile_player_seasons ";
        $qry .= "on {$this->category}_team_profile_player_seasons.player_id = {$this->category}_team_profile_player.player_id ";
        $qry .= "where {$this->category}_team_profile_player.player_id = '{$this->p_id}' limit 1 ";
        $result = mysqli_query($this->conn_game, $qry);
        if ($result) {
            $arr = mysqli_fetch_assoc($result);
            $this->p_position = $arr['player_primary_position'];
            return $arr;
        } else {
            return false;
        }
    }

    // 선수 최근 5 경기 데이터 가져오기
    function get_lastGame() {
        $qry = "select * from {$this->category}_game_player_result ";
        $qry .= "where game_players_id = '{$this->p_id}' ";
        $qry .= "order by game_standard_scheduled desc ";
        $qry .= "limit 5 ";
        $result = mysqli_query($this->conn_game, $qry);
        return $result;
    }

    /*
     * 1. 카테고리에 맞는 함수로 보낸다.
     * 2. 카테고리에 스위치(공격수, 투수) 요소가 있다면 해당 함수로 보낸다
     * 3. 
     */

    function mlb() {

        $primary_arr = $this->get_player_info();
        $game_result = $this->get_lastGame();

        if ($this->p_position == 'RP' || $this->p_position == 'SP') {
            // 선수 기본 데이터 영역 테이블 - 투수
            $res['primary_info'] = $this->make_primary_mlb_P($primary_arr);
            // 5경기 성적 테이블 - 투수
            $res['game_result_info'] = $this->make_tr_result_mlb_P($game_result);
        } else {
            // 선수 기본 데이터 영역 테이블 - 투수
            $res['primary_info'] = $this->make_primary_mlb_F($primary_arr);
            // 5경기 성적 테이블 - 투수
            $res['game_result_info'] = $this->make_tr_result_mlb_F($game_result);
        }
        return $res;
    }
    
    function lol() {

        $primary_arr = $this->get_player_info();
        $game_result = $this->get_lastGame();

        if ($this->p_position == 'RP' || $this->p_position == 'SP') {
            // 선수 기본 데이터 영역 테이블 - 투수
            $res['primary_info'] = $this->make_primary_mlb_P($primary_arr);
            // 5경기 성적 테이블 - 투수
            $res['game_result_info'] = $this->make_tr_result_mlb_P($game_result);
        } else {
            // 선수 기본 데이터 영역 테이블 - 투수
            $res['primary_info'] = $this->make_primary_mlb_F($primary_arr);
            // 5경기 성적 테이블 - 투수
            $res['game_result_info'] = $this->make_tr_result_mlb_F($game_result);
        }
        return $res;
    }

    // 기본 정보 넣기 - 투수
    function make_primary_mlb_P($primary_arr) {
        $salary = numberFormat_for_float($primary_arr['player_salary']);
        $tr = <<<TR
                <h4 class="dp_font_333">
                    {$primary_arr['player_first_name'] } {$primary_arr['player_last_name']}
                </h4>
                <div>
                    <table class="dp_draft_table">
                        <tbody class="dp_font_fff">
                            <tr>
                                <td class="dp_bg_111 text-center">Team</td>
                                <td class="dp_bg_111 text-center">Number</td>
                                <td class="dp_bg_111 text-center">Position</td>
                                <td class="dp_bg_111 text-center">Salary</td>
                                <td class="dp_bg_42698e text-center">ERA</td>
                                <td class="dp_bg_42698e text-center">W-L</td>
                                <td class="dp_bg_42698e text-center">K</td>
                                <td class="dp_bg_42698e text-center">WHIP</td>
                            </tr>
                            <tr style="background: #444">
                                <td id="detail_info_total_prize">
                                    {$primary_arr['team_name']}
                                </td>
                                <td id="detail_info_entry_fee">
                                    {$primary_arr['player_jersey_number']}
                                </td>
                                <td id="detail_info_entry">
                                    {$primary_arr['player_primary_position']}
                                </td>
                                <td id="detail_info_multi_entry">
                                    $ {$salary}
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
TR;
        return $tr;
    }

    // 전적 테이블에 밀어 넣기 - 투수
    function make_tr_result_mlb_P($game_result) {
        $tr = <<<TR
                <tr class="dp_bg_111 text-center">
                    <td>Date</td>
                    <td>Point</td>
                    <td style="width:6%">W</td>
                    <td style="width:5%">L</td>
                    <td style="width:5%">IP</td>
                    <td style="width:5%">HA</td>
                    <td style="width:5%">R</td>
                    <td style="width:5%">ER</td>
                    <td style="width:5%">HR</td>
                    <td style="width:5%">BB</td>
                    <td style="width:5%">K</td>
                    <td style="width:5%">GO</td>
                    <td style="width:5%">FO</td>
                    <td style="width:5%">PIT</td>
                    <td style="width:5%">BF</td>
                    <td style="width:5%">CG</td>
                    <td style="width:5%">SO</td>
                </tr>
TR;
        for ($i = 0; $i < 5; $i++) {
            $fetch_arr = mysqli_fetch_assoc($game_result);
            $json = json_decode($fetch_arr['game_players_statistics_json'], true);

            $overall = $json['pitching']['overall'];
            $onbase = $overall['onbase'];
            $runs = $overall['runs'];
            $outs = $overall['outs'];
            $games = $overall['games'];
            //
            $arr['date'] = '-';
            $arr['point'] = '-';
            $arr['W'] = '-';
            $arr['L'] = '-';
            $arr['IP'] = '-';
            $arr['HA'] = '-';
            $arr['R'] = '-';
            $arr['ER'] = '-';
            $arr['HR'] = '-';
            $arr['BB'] = '-';
            $arr['K'] = '-';
            $arr['GB'] = '-';
            $arr['FB'] = '-';
            $arr['PIT'] = '-';
            $arr['BF'] = '-';
            $arr['CG'] = '-';
            $arr['SO'] = '-';

            if ($fetch_arr['idx']) {
                $arr['date'] = date('Y-m-d', strtotime($fetch_arr['game_standard_scheduled']));
                $arr['point'] = $fetch_arr['game_players_points'];
                $arr['W'] = $games['win'];
                $arr['L'] = $games['loss'];
                $arr['IP'] = $overall['ip_2'];
                $arr['HA'] = $onbase['h'];
                $arr['R'] = $runs['total'];
                $arr['ER'] = $runs['earned'];
                $arr['HR'] = $onbase['hr'];
                $arr['BB'] = $onbase['bb'];
                $arr['K'] = $outs['ktotal'];
                $arr['GB'] = $outs['go'];
                $arr['FB'] = $outs['fo'];
                $arr['PIT'] = $overall['pitch_count'];
                $arr['BF'] = $overall['bf'];
                $arr['CG'] = $games['complete'];
                $arr['SO'] = $games['shutout'];
            }

            $tr .= <<<TR
                <tr style="background: #444; border-bottom:1px solid #666; height:32px">
                    <td>{$arr['date']}</td>
                    <td>
                        <span class="dp_font_00fa00"> 
                            {$arr['point']}
                        </span>
                    </td>
                    <td>{$arr['W']}</td>
                    <td>{$arr['L']}</td>
                    <td>{$arr['IP']}</td>
                    <td>{$arr['HA']}</td>
                    <td>{$arr['R']}</td>
                    <td>{$arr['ER']}</td>
                    <td>{$arr['HR']}</td>
                    <td>{$arr['BB']}</td>
                    <td>{$arr['K']}</td>
                    <td>{$arr['GB']}</td>
                    <td>{$arr['FB']}</td>
                    <td>{$arr['PIT']}</td>
                    <td>{$arr['BF']}</td>
                    <td>{$arr['CG']}</td>
                    <td>{$arr['SO']}</td>
                </tr>
TR;
        }
        return $tr;
    }

    // 기본 정보 넣기 - 타자
    function make_primary_mlb_F($primary_arr) {
        $salary = numberFormat_for_float($primary_arr['player_salary']);
        $tr = <<<TR
                <h4 class="dp_font_333">
                    {$primary_arr['player_first_name'] } {$primary_arr['player_last_name']}
                </h4>
                <div>
                    <table class="dp_draft_table">
                        <tbody class="dp_font_fff">
                            <tr>
                                <td class="dp_bg_111 text-center">Team</td>
                                <td class="dp_bg_111 text-center">Number</td>
                                <td class="dp_bg_111 text-center">Position</td>
                                <td class="dp_bg_111 text-center">Salary</td>
                                <td class="dp_bg_42698e text-center">AVG</td>
                                <td class="dp_bg_42698e text-center">HR</td>
                                <td class="dp_bg_42698e text-center">RBI</td>
                                <td class="dp_bg_42698e text-center">OPS</td>
                            </tr>
                            <tr style="background: #444">
                                <td id="detail_info_total_prize">
                                    {$primary_arr['team_name']}
                                </td>
                                <td id="detail_info_entry_fee">
                                    {$primary_arr['player_jersey_number']}
                                </td>
                                <td id="detail_info_entry">
                                    {$primary_arr['player_primary_position']}
                                </td>
                                <td id="detail_info_multi_entry">
                                    $ {$salary}
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                                <td id="detail_info_my_entry">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
TR;
        return $tr;
    }

    // 전적 테이블에 밀어 넣기 - 타자
    function make_tr_result_mlb_F($game_result) {
        $tr = <<<TR
                <tr class="dp_bg_111 text-center">
                    <td>Date</td>
                    <td>Point</td>
                    <td style="width:6%">AB</td>
                    <td style="width:8%">R</td>
                    <td style="width:8%">H</td>
                    <td style="width:8%">2B</td>
                    <td style="width:8%">3B</td>
                    <td style="width:8%">HR</td>
                    <td style="width:8%">RBI</td>
                    <td style="width:8%">BB</td>
                    <td style="width:8%">K</td>
                    <td style="width:8%">SB</td>
                </tr>
TR;
        for ($i = 0; $i < 5; $i++) {
            $fetch_arr = mysqli_fetch_assoc($game_result);
            $json = json_decode($fetch_arr['game_players_statistics_json'], true);

            $overall = $json['hitting']['overall'];
            $onbase = $overall['onbase'];
            $runs = $overall['runs'];
            $outs = $overall['outs'];
            $steal = $overall['steal'];
            $games = $overall['games'];
            //
            $arr['date'] = '-';
            $arr['point'] = '-';
            $arr['AB'] = '-';
            $arr['R'] = '-';
            $arr['H'] = '-';
            $arr['2B'] = '-';
            $arr['3B'] = '-';
            $arr['HR'] = '-';
            $arr['RBI'] = '-';
            $arr['BB'] = '-';
            $arr['K'] = '-';
            $arr['SB'] = '-';

            if ($fetch_arr['idx']) {
                $arr['date'] = date('Y-m-d', strtotime($fetch_arr['game_standard_scheduled']));
                $arr['point'] = $fetch_arr['game_players_points'];
                $arr['AB'] = $overall['ab'];
                $arr['R'] = $runs['total'];
                $arr['H'] = $onbase['h'];
                $arr['2B'] = $onbase['d'];
                $arr['3B'] = $onbase['t'];
                $arr['HR'] = $onbase['hr'];
                $arr['RBI'] = $overall['rbi'];
                $arr['BB'] = $onbase['bb'];
                $arr['K'] = $outs['ktotal'];
                $arr['SB'] = $steal['stolen'];
            }

            $tr .= <<<TR
                <tr style="background: #444; border-bottom:1px solid #666; height:32px">
                    <td>{$arr['date']}</td>
                    <td>
                        <span class="dp_font_00fa00"> 
                            {$arr['point']}
                        </span>
                    </td>
                    <td>{$arr['AB']}</td>
                    <td>{$arr['R']}</td>
                    <td>{$arr['H']}</td>
                    <td>{$arr['2B']}</td>
                    <td>{$arr['3B']}</td>
                    <td>{$arr['HR']}</td>
                    <td>{$arr['RBI']}</td>
                    <td>{$arr['BB']}</td>
                    <td>{$arr['K']}</td>
                    <td>{$arr['SB']}</td>
                </tr>
TR;
        }
        return $tr;
    }

}
