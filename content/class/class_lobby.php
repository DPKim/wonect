<?php

class LobbyContest extends DB_conn {

    public $gc_idx = null;
    public $category;

    function __construct($cate) {
        $this->conn = $this->dbconnect();
        $this->conn_game = $this->dbconnect_game();
        $this->gc_idx = $cate;
        $this->get_category_info($cate);
    }

    // 카테고리 정보 가져오기
    function get_category_info($idx) {

        if (isset($idx)) {
            $where = "where gc_idx = $idx";
        }

        $qry = "select * from game_category ";
        $qry .= $where;
        
        $result = mysqli_query($this->conn, $qry);

        if ($result) {
            $arr = mysqli_fetch_assoc($result);
            $temp_json = json_decode($arr['gc_pos'], true);
            $position = $temp_json['pos'];

            $info['name'] = $arr['gc_name'];
            $info['position'] = $position['pos'];

            return $this->category = $info;
        } else {
            return false;
        }
    }

    // TOP5 가져오기
    function get_top5() {
        
    }

    // 로비 게임 리스트 쿼리
    function select_game($var) {

        if ($this->gc_idx !== null) {
            $g_sport = "and g_sport = $this->gc_idx ";
        } else {
            $g_sport = '';
        }

        // 시간이 설정되어 있다면
        if (isset($var['date'])) {
            $start_date = $var['date'];
            $time_limit = "and g_date > '$start_date 00:00:00' and g_date < '$start_date 23:59:59' ";
        } else {
            $time_limit = '';
        }

        // 검색어가 있다면
        if (isset($var['search'])) {
            $keyword = mysqli_escape_string($this->conn, urlencode($var['search']));
            $search = "and g_name like '%$keyword%'";
        } else {
            $search = '';
        }

        // 서버 메뉴가 있다면
        if (isset($var['sub_menu'])) {
            switch ($var['sub_menu']) {
                case 1:
                    //free zone
                    $etc = 'and g_fee = 0 ';
                    break;
                case 2:
                    //Below Top5
                    $order = "order by prize desc limit 0, 5 ";
                    break;
//                case 3:
//                    //one to one
//                    $etc = 'and g_size = 2 ';
//                    break;
                case 3:
                    //X2
                    $etc = 'and g_prize = 7 ';
                    break;
                case 4:
                    //X3
                    $etc = 'and g_prize = 8 ';
                    break;
                case 5:
                    //X10
                    $etc = 'and g_prize = 9 ';
                    break;
                case 6:
                    //50/50
                    $etc = 'and g_prize = 1 ';
                    break;
                case 7:
                    //Multi Entry
                    $etc = 'and g_multi_max > 1 ';
                    break;
                default :
                    $etc = '';
                    $order = "order by g_idx desc ";
                    break;
            }
        } else {
            $etc = '';
            $order = "order by g_idx desc ";
        }

        // 게임 가져오기 
        $qry_get_game = "select *, (g_fee * g_size) as prize from game ";
//        $qry_get_game .= "where (g_status = 0 or g_status = 1) ";
        $qry_get_game .= "where g_status not like 3 ";
        $qry_get_game .= $time_limit;
        $qry_get_game .= $g_sport;
        $qry_get_game .= $search;
        $qry_get_game .= $etc;
        $qry_get_game .= $order;
        //
        $res['result'] = mysqli_query($this->conn, $qry_get_game);
        $res['count'] = mysqli_num_rows($res['result']);
        $res['qry'] = $qry_get_game;

        return $res;
    }

    function proccess($var) {

        // 게임 가져오기 
        $result = $this->select_game($var);

        $result_get_game = $result['result'];
        $count_get_game = $result['count'];
        $list_lobby = '';

        for ($i = 0; $i < $count_get_game; $i++) {
            $arr_get_game = mysqli_fetch_assoc($result_get_game);
            ////
            $rank_reward = new RankReward($arr_get_game['g_size'], $arr_get_game['g_fee'], $arr_get_game['g_prize']);

            $fee = numberFormat_for_float($arr_get_game['g_fee']);
            $total_prize = numberFormat_for_float($rank_reward->total_reward);

            // 시간대에 맞는 날짜로 변경
            $newdate = $this->change_timezone($arr_get_game['g_date'], $arr_get_game['g_timezone'], $var['locale']);

            $g_date = new DateTime($newdate['day']);
            $g_week = $this->daily_sm[$g_date->format("w")];
            $g_hour = $g_date->format("H");
            $g_min = $g_date->format("i");

            $img_src = INC_PUBLIC;
            $title = $arr_get_game['g_name'];

            if ($arr_get_game['g_status'] == 2) {
                $list_lobby .=<<<LIST
            <tr style="font-size:12px; background: #eee; border-bottom: 1px solid #ccc" >
                <td style="width:5%; padding: 5px">
                    <img src="{$img_src}images/icon/{$this->category['name']}.png" width=30px>
                </td>
                <td style="padding: 5px" class="dp_lobby_tb_content" data-list-index = {$arr_get_game['g_idx']}>
                    <span>$title</span>
                </td>
                <td style="width:8%; text-align:center; padding: 5px">
                    {$fee} G
                </td>
                <td style="width:8%; text-align:center; padding: 5px">
                    <strong class="dp_font_5ea600">{$total_prize} G</strong>
                </td>
                <td style="width:8%; text-align:center; padding: 5px">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> 
                    {$arr_get_game['g_entry']}/{$arr_get_game['g_size']}
                </td>
                <td style="width:8%; text-align:right; padding: 5px">
                    <span>{$g_week} $g_hour:$g_min</span>
                </td>
                <td style="width:7%; padding: 5px">
                    <button class="btn btn-warning btn-sm btn_live" data-list-index="{$arr_get_game['g_idx']}">LIVE</button>
                </td>
            </tr>
LIST;
            } else {
                $list_lobby .=<<<LIST
            <tr style="font-size:12px; background: #eee; border-bottom: 1px solid #ccc" >
                <td style="width:5%; padding: 5px">
                    <img src="{$img_src}images/icon/{$this->category['name']}.png" width=30px>
                </td>
                <td style="padding: 5px" class="dp_lobby_tb_content" data-list-index = {$arr_get_game['g_idx']}>
                    <span>$title</span>
                </td>
                <td style="width:8%; text-align:center; padding: 5px">
                    {$fee} G
                </td>
                <td style="width:8%; text-align:center; padding: 5px">
                    <strong class="dp_font_5ea600">{$total_prize} G</strong>
                </td>
                <td style="width:8%; text-align:center; padding: 5px">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> 
                    {$arr_get_game['g_entry']}/{$arr_get_game['g_size']}
                </td>
                <td style="width:8%; text-align:right; padding: 5px">
                    <span>{$g_week} $g_hour:$g_min</span>
                </td>
                <td style="width:7%; padding: 5px">
                    <button class="btn btn-primary btn-sm btn-draft" data-game= {$arr_get_game['g_idx']}>ENTER</button>
                </td>
            </tr>
LIST;
            }
        }
        return $list_lobby;
    }

    // 시간 탭 만들기
    function time_tab_lobby() {

        $lobby_game = new CreateGame(0, $this->category);
        $result_schedule = $lobby_game->qry_function()['result'];
        $count_schedule = $lobby_game->qry_function()['count'];

        for ($i = 0; $i < $count_schedule; $i++) {
            $arr_schedule = mysqli_fetch_assoc($result_schedule);

            // 선택용 li 만들기
            $ddate = $arr_schedule['games_scheduled'];
            $date = new DateTime($ddate);
            $year = $date->format("Y");
            $mon = $date->format("m");
            $week = $date->format("w");
            $day = $date->format("d");
            $hour = $date->format("H");
            $min = $date->format("i");

            $game_id['year'] = $year;
            $game_id['mon'] = $mon;
            $game_id['day'] = $day;
            $game_id['hour'] = $hour;
            $game_id['min'] = $min;

            // 첫번째 리스트를 픽
            if ($i == 0) {
                $dp_lobby_group_pick = 'dp_lobby_group_pick';
            } else {
                $dp_lobby_group_pick = '';
            }

            $li_date .=<<< li
                    <div class="dp_lobby_group $dp_lobby_group_pick" data-date = '$year-$mon-$day'>
                        <div><strong class="dp_font_fff">$hour:$min • {$this->daily_sm['$week']} $day / $mon</strong></div>
                        <div class="dp_float_left">{$arr_schedule['count']} Games</div>
                        <div class="dp_float_right">{$this->category['']}</div>
                        <div class="dp_lobby_bar"></div>
                    </div>
li;

            return $li_date;
        }
    }

}
