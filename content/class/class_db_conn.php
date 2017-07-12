<?php

class DB_conn {

    public $conn, $conn_game, $qry, $arr;
    public $get_cate;
    //
    public $locale;
    public $today;
    public $today_time;
    public $today_date;
    //
    public $daily = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    public $daily_sm = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

    // 시간대 변경
    function setTimezone($locale) {
        if (!isset($locale)) {
            if (isset($_SESSION['LOCALE']['locale'])) {
                $locale = $_SESSION['LOCALE']['locale'];
            } else {
                $locale = 'GMT';
            }
            $this->locale = $locale;
        } else {
            $this->locale = $locale;
        }

        date_default_timezone_set($this->locale);
        $this->today = date('Y-m-d H:i:s');
        $this->today_time = strtotime($this->today);
        $this->today_date = date('Y-m-d');
    }

    // TIMEZONE 변경하기
    function change_timezone($datetime, $origin_timezone, $target_timezone) {
        $date = new DateTime($datetime, new DateTimeZone($origin_timezone));
        //
        $date->setTimezone(new DateTimeZone($target_timezone));
        $today = $date->format('Y-m-d H:i:s');
        //
        $today_time = strtotime($today);
        $today_date = date('Y-m-d', $today_time);

        $newdate['day'] = $today;
        $newdate['date'] = $today_date;

        return $newdate;
    }

    // 현재 접속한 곳이 로컬 환경인가?
    function chk_localhost() {
        $server_host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        if ($server_host == 'localhost') {
            return true;
        } else {
            return false;
        }
    }

    function dbconnect() {

        if ($this->chk_localhost() == true) {
            $host = 'localhost';
        } else {
            $host = 'localhost';
        }

        $user = 'fly2024';
        $password = 'fly2024!@#';
        $database = 'spobit_web';

        $conn = mysqli_connect($host, $user, $password, $database);
        mysqli_set_charset($conn, "utf8");

        if (!$conn) {
            echo 'DB connect error';
        } else {
            return $this->conn = $conn;
        }
    }

    function dbconnect_game() {

        if ($this->chk_localhost() == true) {
            $host = '106.243.82.236';
        } else {
            $host = '192.168.38.250';
        }
        $user = 'spogame';
        $password = 'Spd!*3htfG*68';
        $database = 'spobit_games';
        $port = 4406;

        $conn_game = mysqli_connect($host, $user, $password, $database, $port);
        mysqli_set_charset($conn_game, "utf8");

        if (!$conn_game) {
            echo 'Game DB connect error';
        } else {
            return $this->conn_game = $conn_game;
        }
    }

    function setConn() {
        $this->conn = $this->dbconnect();
    }

    function getQry() {
        return $this->qry;
    }

    function setQry($qry) {
        $this->qry = $qry;
    }

    function resultQry() {
        return mysqli_query($this->conn, $this->qry);
    }

    function countResult() {
        return mysqli_num_rows($this->resultQry());
    }

    function arrQry() {
        return mysqli_fetch_assoc($this->resultQry());
    }

    function forArr() {
        for ($i = 0; $i < $this->countResult(); $i++) {
            $this->arr[$i] = $this->arrQry();
        }
        return $this->arr;
    }

    // DB fetch 함수
    function fetchDB_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    //카테고리 배열로 만들기
    function getCategoryDB($idx) {

        if ($idx !== 0) {
            $where = "where gc_idx = $idx ";
        } else {
            $where = ' ';
        }
        $qry = "select * from game_category $where";
        $result = mysqli_query($this->conn, $qry);
        if ($result) {
            $count = mysqli_num_rows($result);
            for ($i = 0; $i < $count; $i++) {
                $arr[$i] = mysqli_fetch_assoc($result);
            }
            $this->get_cate = $arr;
            return $arr;
        } else {
            return $qry;
        }
    }

}
