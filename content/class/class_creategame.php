<?php

class CreateGame extends DB_conn {

    public $step;
    public $category;

    function __construct($step, $category) {
        $this->dbconnect();
        $this->dbconnect_game();

        if (isset($step)) {
            $this->step = $step;
        } else {
            $this->step = 0;
        }

        $this->category = $category;

        $this->setTimezone();
        $this->get_cate = $this->getCategoryDB($this->category);
    }

    // date의 00시 가져오기
    function get_0_hour($dateTime) {
        return date('Y-m-d 00:00:00');
    }

    // 시간 탭 만들기
    function make_tab_date($datetime, $locale) {

        if (isset($datetime)) {
            $date = $datetime;
        } else {
            $date = $this->today_date;
        }

        $result_schedule = $this->qry_function($date)['result'];
        $count_schedule = $this->qry_function($date)['count'];
        $cate_name = $this->get_cate[0]['gc_name'];

        for ($i = 0; $i < $count_schedule; $i++) {
            $arr_schedule = mysqli_fetch_assoc($result_schedule);

            // 선택용 li 만들기
            $ddate = $this->change_timezone($arr_schedule['games_timezone_scheduled'], $arr_schedule['games_timezone_type'], $locale);
            $date = new DateTime($ddate['day']);
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
            
            // 오리지널 날짜
            $date_origin = date('Y-m-d', strtotime($arr_schedule['games_timezone_scheduled']));

            // 첫번째 리스트를 픽
            if ($i == 0) {
                $dp_lobby_group_pick = 'dp_lobby_group_pick';
            } else {
                $dp_lobby_group_pick = '';
            }

            $li_date .=<<< li
                    <div class="dp_lobby_group $dp_lobby_group_pick" data-date = '$date_origin'>
                        <div><strong class="dp_font_fff">$hour:$min • {$this->daily_sm[$week]} $day / $mon</strong></div>
                        <div class="dp_float_left">{$arr_schedule['count']} Games</div>
                        <div class="dp_float_right">{$cate_name}</div>
                        <div class="dp_lobby_bar"></div>
                    </div>
li;
        }
        return $li_date;
    }

    function proccess_1() {
        // 현재 스케줄이 남아 있는 게임을 DB에서 가져올 것
        $count_cate = count($this->get_cate);

        for ($i = 0; $i < $count_cate; $i++) {
            $cate_name = $this->get_cate[$i]['gc_name'];

            // 스케줄 이름
            $lower_cate_name = strtolower($cate_name);
            $game_schedule_name = $lower_cate_name . '_game_daily_schedule';

            $qry = "select * from $game_schedule_name where games_rescheduled_reason = '' ";
            $qry .= "and games_timezone_scheduled > ('$this->today_date' + INTERVAL 1 DAY) ";
            $result = mysqli_query($this->conn_game, $qry);
            
//            var_dump($qry);

            $public = INC_PUBLIC;

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    // 해당 게임은 노출
                    $this_game['li'] .=<<< TG
                        <li data-index="{$this->get_cate[$i]['gc_idx']}" class='active'>
                            <img src="$public/images/active-{$lower_cate_name}.png">
                            <br>
                            <span>{$this->get_cate[$i]['gc_name']}</span>
                        </li>
TG;
                    $this_game['name'][$i] = $cate_name;
                    $this_game['gc_idx'][$i] = $this->get_cate[$i]['gc_idx'];
                } else {
                    continue;
                }
            }
        }
        return $this_game;
    }

    function qry_function($datetime) {
        if (isset($datetime)) {
            $this_date = $datetime;
        } else {
            $this_date = $this->today_date;
        }

        $lower_cate_name = strtolower($this->get_cate[0]['gc_name']);
        $game_schedule_name = $lower_cate_name . '_game_daily_schedule';

        $qry_schedule = "select count(*) as count, games_timezone_type, MIN(games_timezone_scheduled) as games_timezone_scheduled ";
        $qry_schedule .= "from $game_schedule_name ";
        $qry_schedule .= "where games_timezone_scheduled >= '$this_date 00:00:00' and  games_timezone_scheduled < '$this_date 00:00:00' +INTERVAL 6 DAY ";
        $qry_schedule .= "group by date(games_timezone_scheduled) ";
        $qry_schedule .= "order by games_timezone_scheduled asc";
        $result['result'] = mysqli_query($this->conn_game, $qry_schedule);
        $result['count'] = mysqli_num_rows($result['result']);
        $result['qry'] = $qry_schedule;

        return $result;
    }

    function proccess_2() {

        // 카테고리
        $cate_name = $this->get_cate[0]['gc_name'];
        $lower_cate_name = strtolower($cate_name);
        $game_schedule_name = $lower_cate_name . '_game_daily_schedule';

        $result_schedule = $this->qry_function()['result'];
        $count_schedule = $this->qry_function()['count'];


        $section_daily = '';
        for ($i = 0; $i < $count_schedule; $i++) {
            $arr_schedule = mysqli_fetch_assoc($result_schedule);
            // 선택용 li 만들기
            $ddate = $arr_schedule['games_timezone_scheduled'];
            $date = new DateTime($ddate);
            $year = $date->format("Y");
            $mon = $date->format("M");
            $week = $date->format("w");
            $day = $date->format("d");
            $hour = $date->format("H");
            $min = $date->format("i");

            $code = $cate_name . '-' . $year . $mon . $day;

            $game_id['year'] = $year;
            $game_id['mon'] = $mon;
            $game_id['day'] = $day;
            $game_id['week'] = $this->daily[$week];
            $game_id['hour'] = $hour;
            $game_id['min'] = $min;

            // 각 날짜 별 세부 게임 스케줄 가져오기
            $qry_daily = "select * from $game_schedule_name where games_timezone_scheduled > DATE_FORMAT('{$arr_schedule['games_timezone_scheduled']}','%Y-%m-%d 00:00:00') ";
            $qry_daily .= "and games_timezone_scheduled < DATE_FORMAT('{$arr_schedule['games_timezone_scheduled']}','%Y-%m-%d 23:59:59') ";
            $qry_daily .= "group by games_home_id ";
            $qry_daily .= "order by games_timezone_scheduled asc";

            $result_daily = mysqli_query($this->conn_game, $qry_daily);
            $count_daily = mysqli_num_rows($result_daily);

            $section_daily .= '<ul class="section-detail ' . $code . '">';

            $game_id['timezone'] = $arr_schedule['games_timezone_type'];
            $game_id['game'] = $cate_name;
            $game_id['year'] = $year;
            $game_id['mon'] = $mon;
            $game_id['day'] = $day;
            $game_id['week'] = $this->daily[$week];
            $game_id['hour'] = $hour;
            $game_id['min'] = $min;
            $game_id['count'] = $count_daily;
            $game_id['detail'] = array();

            for ($j = 0; $j < $count_daily; $j++) {
                $arr_daily = mysqli_fetch_assoc($result_daily);
                //
                $game_time = date('H:i', strtotime($arr_daily['games_timezone_scheduled']));
                //
                $game_id['detail'][$j]['game_id'] = $arr_daily['games_id'];
                $game_id['detail'][$j]['home_id'] = $arr_daily['games_home_id'];
                $game_id['detail'][$j]['away_id'] = $arr_daily['games_away_id'];

                if ($j + 1 == $count_daily) {
                    $json_gam_id = json_encode($game_id);
                    $json_data[$i] = "<div class='div-$code' data-index='$json_gam_id'></div>";
                }

                $section_daily .=<<< Day
                        <li>
                            {$arr_daily['games_home_name']} @ {$arr_daily['games_away_name']}<br>
                            {$game_time}
                        </li>
Day;
            }
            $section_daily .= '</ul>' . $json_data[$i];
            consoleLog(json_encode($game_id));
            $data_games = urlencode(json_encode($game_id));
            $li_date .=<<< li
                    <li data-index="1" class="schedule active" data-code ="$code" data-games="$data_games">
                        <span>
                            {$this->daily[$week]}<br>
                            $day $mon
                        </span>
                        <br>
                        <div class="date">
                            $hour:$min
                        </div>
                        <div class="box">{$arr_schedule['count']} games</div>
                    </li>
li;
        }
        $res['li_game'] = $li_date;
        $res['daily_game'] = $section_daily;
        return $res;
    }

}
