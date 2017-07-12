<?php

class RankReward {

    public $entry_size;
    public $entry_fee;
    public $reward_type;
    //
    public $total_reward;
    public $last_rank;
    public $sum_reward = array();

    function __construct($entry_size, $entry_fee, $reward_type) {
        $this->entry_size = $entry_size;
        $this->entry_fee = $entry_fee;
        $this->reward_type = $reward_type;

        // 총상금 계산
        $this->total_reward = floor($entry_size * $entry_fee - ($entry_size * $entry_fee * 0.1));
    }

    // 랭킹을 뽑아 배열로 만들기
    function make_rank_arr() {
        switch ($this->reward_type) {
            case 0:
                $rank_arr = $this->t1();
                break;
            case 1:
                $rank_arr = $this->half();
                break;
            case 2:
                $rank_arr = $this->t2();
                break;
            case 3:
                $rank_arr = $this->t3();
                break;
            case 4:
                $rank_arr = $this->t4();
                break;
            case 5:
                $rank_arr = $this->t5();
                break;
            case 6:
                $rank_arr = $this->multi();
                break;
            case 7:
                $rank_arr = $this->x(2);
                break;
            case 8:
                $rank_arr = $this->x(3);
                break;
            case 9:
                $rank_arr = $this->x(10);
                break;
        }
        return $rank_arr;
    }

    function x($x) {
        // 최대 인원에 대한 공식
        $reward = $this->entry_fee * $x;
        $max = floor($this->total_reward / $reward);

        if ($max <= 1) {
            return false;
        }
        //
        $rank_name = '1~' . $max;
        $rank_arr[0]['rank'] = $rank_name;
        $rank_arr[0]['reward'] = $reward;
        $rank_arr[0]['limit'] = $max;
        //
        return $rank_arr;
    }

    // winner takes all
    function t1() {
        for ($i = 0; $i < 1; $i++) {
            $rank_name = $this->make_rank_name($i);
            $rank_arr[$i]['rank'] = $rank_name;
            $rank_arr[$i]['reward'] = $this->total_reward;
            $rank_arr[$i]['limit'] = 1;
        }
        return $rank_arr;
    }

    // top2
    function t2() {
        if ($this->entry_size > 3) {
            $this->last_rank = $this->total_reward;

            for ($i = 0; $i < 2; $i++) {
                $rank_name = $this->make_rank_name($i);

                // 각 랭크에 따른 계산식
                if ($i == 0) {
                    $reward = round($this->total_reward * (4 / 7));
                    $this->last_rank -= $reward;
                } else if ($i == 1) {
                    $reward = $this->last_rank;
                }

                $rank_arr[$i]['rank'] = $rank_name;
                $rank_arr[$i]['reward'] = $reward;
                $rank_arr[$i]['limit'] = 1;
            }
            return $rank_arr;
        } else {
            return false;
        }
    }

    // top3
    function t3() {
        if ($this->entry_size > 6) {
            $this->last_rank = $this->total_reward;

            for ($i = 0; $i < 3; $i++) {
                $rank_name = $this->make_rank_name($i);

                // 각 랭크에 따른 계산식
                if ($i == 0) {
                    $reward = round($this->total_reward * (4 / 9));
                    $this->last_rank -= $reward;
                } else if ($i == 1) {
                    $reward = round($this->total_reward * (3 / 9));
                    $this->last_rank -= $reward;
                } else if ($i == 2) {
                    $reward = $this->last_rank;
                }

                $rank_arr[$i]['rank'] = $rank_name;
                $rank_arr[$i]['reward'] = $reward;
                $rank_arr[$i]['limit'] = 1;
            }
            return $rank_arr;
        } else {
            return false;
        }
    }

    // top4
    function t4() {
        if ($this->entry_size > 9) {
            $this->last_rank = $this->total_reward;

            for ($i = 0; $i < 4; $i++) {
                $rank_name = $this->make_rank_name($i);

                // 각 랭크에 따른 계산식
                if ($i == 0) {
                    $reward = round($this->total_reward * (6 / 18));
                    $this->last_rank -= $reward;
                } else if ($i == 1) {
                    $reward = round($this->total_reward * (5 / 18));
                    $this->last_rank -= $reward;
                } else if ($i == 2) {
                    $reward = round($this->total_reward * (4 / 18));
                    $this->last_rank -= $reward;
                } else if ($i == 3) {
                    $reward = $this->last_rank;
                }

                $rank_arr[$i]['rank'] = $rank_name;
                $rank_arr[$i]['reward'] = $reward;
                $rank_arr[$i]['limit'] = 1;
            }
            return $rank_arr;
        } else {
            return false;
        }
    }

    // top5
    function t5() {
        if ($this->entry_size > 13) {
            $this->last_rank = $this->total_reward;

            for ($i = 0; $i < 5; $i++) {
                $rank_name = $this->make_rank_name($i);

                // 각 랭크에 따른 계산식
                if ($i == 0) {
                    $reward = round($this->total_reward * (7 / 25));
                    $this->last_rank -= $reward;
                } else if ($i == 1) {
                    $reward = round($this->total_reward * (6 / 25));
                    $this->last_rank -= $reward;
                } else if ($i == 2) {
                    $reward = round($this->total_reward * (5 / 25));
                    $this->last_rank -= $reward;
                } else if ($i == 3) {
                    $reward = round($this->total_reward * (4 / 25));
                    $this->last_rank -= $reward;
                } else if ($i == 4) {
                    $reward = $this->last_rank;
                }

                $rank_arr[$i]['rank'] = $rank_name;
                $rank_arr[$i]['reward'] = $reward;
                $rank_arr[$i]['limit'] = 1;
            }
            return $rank_arr;
        } else {
            return false;
        }
    }

    // half
    function half() {
        // 총 인원이 6명 이상인 경우만
        if ($this->entry_size >= 6) {

            // 총 인원이 짝수인지 유효성 체크
            if ($this->entry_size % 2 == 0) {
                $half = $this->entry_size / 2;
                $reward = floor($this->total_reward / $half);

                $rank_arr[0]['rank'] = '50/50';
                $rank_arr[0]['reward'] = $reward;
                $rank_arr[0]['limit'] = $half;

                return $rank_arr;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // multi
    function multi() {
        if ($this->entry_size > 27) {
            // 명수에 따른 구간 함수 찾기
            if ($this->entry_size > 27 && $this->entry_size < 32) {
                return $this->cal_multi(1);
            } else if ($this->entry_size > 31 && $this->entry_size < 44) {
                return $this->cal_multi(2);
            } else if ($this->entry_size > 43 && $this->entry_size < 64) {
                return $this->cal_multi(3);
            } else if ($this->entry_size > 63 && $this->entry_size < 84) {
                return $this->cal_multi(4);
            } else if ($this->entry_size > 83 && $this->entry_size < 102) {
                return $this->cal_multi(5);
            } else if ($this->entry_size > 101 && $this->entry_size < 144) {
                return $this->cal_multi(6);
            } else if ($this->entry_size > 143 && $this->entry_size < 184) {
                return $this->cal_multi(7);
            } else if ($this->entry_size > 183 && $this->entry_size < 224) {
                return $this->cal_multi(8);
            } else if ($this->entry_size > 223 && $this->entry_size < 324) {
                return $this->cal_multi(9);
            } else if ($this->entry_size > 323 && $this->entry_size < 424) {
                return $this->cal_multi(10);
            } else if ($this->entry_size > 423 && $this->entry_size < 624) {
                return $this->cal_multi(11);
            } else if ($this->entry_size > 623 && $this->entry_size < 824) {
                return $this->cal_multi(12);
            } else if ($this->entry_size > 823 && $this->entry_size < 1224) {
                return $this->cal_multi(13);
            } else if ($this->entry_size > 1223 && $this->entry_size < 1624) {
                return $this->cal_multi(14);
            } else if ($this->entry_size > 1623 && $this->entry_size < 2024) {
                return $this->cal_multi(15);
            } else if ($this->entry_size > 2023 && $this->entry_size < 3224) {
                return $this->cal_multi(16);
            } else if ($this->entry_size > 3223 && $this->entry_size < 5224) {
                return $this->cal_multi(17);
            } else if ($this->entry_size > 5223 && $this->entry_size < 9224) {
                return $this->cal_multi(18);
            } else if ($this->entry_size > 9223 && $this->entry_size < 17224) {
                return $this->cal_multi(19);
            }
        } else {
            return false;
        }
    }

    function cal_multi($type) {

        if ($type < 6) {
            $function_name = 'multi_preiod_' . $type;
            return $this->$function_name();
        } else {
            return $this->multi_preiod_define($type);
        }
    }

    // 멀티 랭킹 계산 함수
    function multi_preiod_1() {
        $this->last_rank = $this->total_reward;

        for ($i = 0; $i < 6; $i++) {
            $rank_name = $this->make_rank_name($i);

            // 각 랭크에 따른 계산식
            if ($i == 0) {
                $limit = 1;
                $reward = floor($this->total_reward * (11 / 50));
                $this->last_rank -= $reward;
            } else if ($i == 1) {
                $limit = 1;
                $reward = floor($this->total_reward * (10 / 50));
                $this->last_rank -= $reward;
            } else if ($i == 2) {
                $limit = 1;
                $reward = floor($this->total_reward * (9 / 50));
                $this->last_rank -= $reward;
            } else if ($i == 3) {
                $limit = 1;
                $reward = floor($this->total_reward * (8 / 50));
                $this->last_rank -= $reward;
            } else if ($i == 4) {
                $limit = 1;
                $reward = floor($this->total_reward * (6 / 50));
                $this->last_rank -= $reward;
            } else if ($i == 5) {
                $limit = 2;
                $reward = floor($this->last_rank / $limit);
            }

            $rank_arr[$i]['rank'] = $rank_name;
            $rank_arr[$i]['reward'] = $reward;
            $rank_arr[$i]['limit'] = $limit;
        }
        return $rank_arr;
    }

    function multi_preiod_2() {
        $this->last_rank = $this->total_reward;

        for ($i = 0; $i < 7; $i++) {
            $rank_name = $this->make_rank_name($i);

            // 각 랭크에 따른 계산식
            if ($i == 0) {
                $limit = 1;
                $reward = round($this->total_reward * (11 / 57));
                $this->last_rank -= $reward;
            } else if ($i == 1) {
                $limit = 1;
                $reward = floor($this->total_reward * (10 / 57));
                $this->last_rank -= $reward;
            } else if ($i == 2) {
                $limit = 1;
                $reward = round($this->total_reward * (8 / 57));
                $this->last_rank -= $reward;
            } else if ($i == 3) {
                $limit = 1;
                $reward = round($this->total_reward * (7 / 57));
                $this->last_rank -= $reward;
            } else if ($i == 4) {
                $limit = 1;
                $reward = round($this->total_reward * (6 / 57));
                $this->last_rank -= $reward;
            } else if ($i == 5) {
                $limit = 2;
                $cal = $this->total_reward * (9 / 57);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 6) {
                $limit = 2;
                $reward = round($this->last_rank / $limit);
            }

            $rank_arr[$i]['rank'] = $rank_name;
            $rank_arr[$i]['reward'] = $reward;
            $rank_arr[$i]['limit'] = $limit;
        }
        return $rank_arr;
    }

    function multi_preiod_3() {
        $this->last_rank = $this->total_reward;

        for ($i = 0; $i < 8; $i++) {
            $rank_name = $this->make_rank_name($i);

            // 각 랭크에 따른 계산식
            if ($i == 0) {
                $limit = 1;
                $reward = round($this->total_reward * (14 / 79));
                $this->last_rank -= $reward;
            } else if ($i == 1) {
                $limit = 1;
                $reward = round($this->total_reward * (12 / 79));
                $this->last_rank -= $reward;
            } else if ($i == 2) {
                $limit = 1;
                $reward = round($this->total_reward * (11 / 79));
                $this->last_rank -= $reward;
            } else if ($i == 3) {
                $limit = 1;
                $reward = round($this->total_reward * (10 / 79));
                $this->last_rank -= $reward;
            } else if ($i == 4) {
                $limit = 1;
                $reward = round($this->total_reward * (9 / 79));
                $this->last_rank -= $reward;
            } else if ($i == 5) {
                $limit = 2;
                $cal = $this->total_reward * (9 / 79);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 6) {
                $limit = 2;
                $cal = $this->total_reward * (8 / 79);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 7) {
                $limit = 2;
                $reward = round($this->last_rank / $limit);
            }

            $rank_arr[$i]['rank'] = $rank_name;
            $rank_arr[$i]['reward'] = $reward;
            $rank_arr[$i]['limit'] = $limit;
        }
        return $rank_arr;
    }

    function multi_preiod_4() {
        $this->last_rank = $this->total_reward;

        for ($i = 0; $i < 9; $i++) {
            $rank_name = $this->make_rank_name($i);

            // 각 랭크에 따른 계산식
            if ($i == 0) {
                $limit = 1;
                $reward = round($this->total_reward * (14 / 115));
                $this->last_rank -= $reward;
            } else if ($i == 1) {
                $limit = 1;
                $reward = round($this->total_reward * (13 / 115));
                $this->last_rank -= $reward;
            } else if ($i == 2) {
                $limit = 1;
                $reward = round($this->total_reward * (12 / 115));
                $this->last_rank -= $reward;
            } else if ($i == 3) {
                $limit = 1;
                $reward = round($this->total_reward * (11 / 115));
                $this->last_rank -= $reward;
            } else if ($i == 4) {
                $limit = 1;
                $reward = round($this->total_reward * (10 / 115));
                $this->last_rank -= $reward;
            } else if ($i == 5) {
                $limit = 2;
                $cal = $this->total_reward * (15 / 115);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 6) {
                $limit = 2;
                $cal = $this->total_reward * (14 / 115);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 7) {
                $limit = 2;
                $cal = $this->total_reward * (11 / 115);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 8) {
                $limit = 5;
                $reward = round($this->last_rank / $limit);
            }

            $rank_arr[$i]['rank'] = $rank_name;
            $rank_arr[$i]['reward'] = $reward;
            $rank_arr[$i]['limit'] = $limit;
        }
        return $rank_arr;
    }

    function multi_preiod_5() {
        $this->last_rank = $this->total_reward;

        for ($i = 0; $i < 10; $i++) {
            $rank_name = $this->make_rank_name($i);

            // 각 랭크에 따른 계산식
            if ($i == 0) {
                $limit = 1;
                $reward = round($this->total_reward * (15 / 151));
                $this->last_rank -= $reward;
            } else if ($i == 1) {
                $limit = 1;
                $reward = round($this->total_reward * (14 / 151));
                $this->last_rank -= $reward;
            } else if ($i == 2) {
                $limit = 1;
                $reward = round($this->total_reward * (13 / 151));
                $this->last_rank -= $reward;
            } else if ($i == 3) {
                $limit = 1;
                $reward = round($this->total_reward * (12 / 151));
                $this->last_rank -= $reward;
            } else if ($i == 4) {
                $limit = 1;
                $reward = round($this->total_reward * (11 / 151));
                $this->last_rank -= $reward;
            } else if ($i == 5) {
                $limit = 2;
                $cal = $this->total_reward * (16 / 151);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 6) {
                $limit = 2;
                $cal = $this->total_reward * (15 / 151);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 7) {
                $limit = 2;
                $cal = $this->total_reward * (14 / 151);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 8) {
                $limit = 5;
                $cal = $this->total_reward * (26 / 151);
                $reward = round($cal / $limit);
                $this->last_rank -= $cal;
            } else if ($i == 9) {
                $limit = 5;
                $reward = round($this->last_rank / $limit);
            }

            $rank_arr[$i]['rank'] = $rank_name;
            $rank_arr[$i]['reward'] = $reward;
            $rank_arr[$i]['limit'] = $limit;
        }
        return $rank_arr;
    }

    function multi_preiod_define($type) {
        $this->last_rank = $this->total_reward;
        return $this->define_var($type);
    }

    // 변동 수치에 대한 상수값 정의
    function define_var($type) {

        switch ($type) {
            case 6:
                $define['gold'] = array(19, 15, 12, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5);
                $define['bouns'] = array(0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
                break;
            case 7:
                $define['gold'] = array(26, 19, 15, 13, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10);
                $define['bouns'] = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0.2, 0.1);
                break;
            case 8:
                $define['gold'] = array(33, 23, 20, 16, 13, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10);
                $define['bouns'] = array(2, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0);
                break;
            case 9:
                $define['gold'] = array(40, 30, 20, 15, 13, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10);
                $define['bouns'] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 10:
                $define['gold'] = array(58, 43, 31, 22, 16, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25);
                $define['bouns'] = array(0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 11:
                $define['gold'] = array(65, 49, 39, 29, 19, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25);
                $define['bouns'] = array(10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 12:
                $define['gold'] = array(112, 84, 63, 46, 34, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50);
                $define['bouns'] = array(-12, -4, -3, -6, -4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 13:
                $define['gold'] = array(148, 102, 68, 45, 29, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50, 50);
                $define['bouns'] = array(-8, -2, -8, -5, -1, 0, 0, 0, -0.3, 0, 0, 0, -0.3, 0, 0, 0, 0, 0);
                break;
            case 14:
                $define['gold'] = array(220, 143, 98, 65, 42, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50, 50, 100);
                $define['bouns'] = array(1, 1, 1, 5, 8, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 15:
                $define['gold'] = array(292, 175, 105, 63, 37, 20, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50, 50, 100, 100);
                $define['bouns'] = array(0, 0, 0, 0, 0, 0, 3, 3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 16:
                $define['gold'] = array(360, 210, 130, 75, 45, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50, 50, 100, 100, 100);
                $define['bouns'] = array(0, 0, 0, 0, 0, 3, 2, 2, 2, 2, 2, 2, 2, 1, 1, 1, 1, 1, 1, 1, 0);
                break;
            case 17:
                $define['gold'] = array(465, 275, 165, 100, 60, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50, 50, 100, 100, 100, 300);
                $define['bouns'] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 18:
                $define['gold'] = array(715, 425, 255, 150, 90, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50, 50, 100, 100, 100, 300, 500);
                $define['bouns'] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                break;
            case 19:
                $define['gold'] = array(1000, 600, 360, 125, 125, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3);
                $define['limit'] = array(1, 1, 1, 1, 1, 2, 2, 2, 5, 5, 5, 10, 10, 10, 25, 25, 50, 50, 100, 100, 100, 300, 500, 1000);
                $define['bouns'] = array(0, 0, 0, 0, 0, -8, -7, -7, -7, -7, -6, -6, -5, -5, -5, -4, -4, -3, -3, -2, -2, -1, -1, -1);
                break;
        }

        $standard = $this->cal_standard($define);
        $result_define = $this->cal_define_var($standard);
        return $result_define;
    }

    //상수 값 연산
    function cal_define_var($standard) {
        $define_gold = $standard['gold'];
        $define_limit = $standard['limit'];
        //
        $sum = $standard['sum'];
        //
        $count = $standard['count'];

        for ($i = 0; $i < $count; $i++) {
            $rank_name = $this->make_rank_name($i);
            $limit = $define_limit[$i];
            if ($i == $count - 1) {
                // 마지막 행렬을 수식 변경
                $reward = round($this->last_rank / $limit);

                $rank_arr[$i]['rank'] = $rank_name;
                $rank_arr[$i]['reward'] = $reward;
                $rank_arr[$i]['limit'] = $limit;
            } else {
                $reward = round($this->total_reward * ($define_gold[$i] / $sum));
                if ($limit > 1) {
                    $reward = round(($this->total_reward * ($define_gold[$i] / $sum)) / $limit);
                }
                $this->last_rank -= $reward * $limit;

                $rank_arr[$i]['rank'] = $rank_name;
                $rank_arr[$i]['reward'] = $reward;
                $rank_arr[$i]['limit'] = $limit;
            }
        }
        return $rank_arr;
    }

    // 고정 값이 존재함 이에 따른 수식 변화가 추가됨
    function cal_standard($define) {
        $count = count($define['gold']);
        for ($i = 0; $i < $count; $i++) {
            $gold = $define['gold'][$i];
            $limit = $define['limit'][$i];
            $bouns = $define['bouns'][$i];

            $temp_gold = $gold * $limit;
            $temp_bonus = $limit * $bouns;

            $standard['gold'][$i] = $temp_gold + $temp_bonus;
            $standard['limit'][$i] = $limit;
        }
        $standard['sum'] = array_sum($standard['gold']);
        $standard['count'] = $count;
        return $standard;
    }

    // 순위 명칭
    function make_rank_name($rank) {
        switch ($rank) {
            case 0:
                return '1st';
            case 1:
                return '2nd';
            case 2:
                return '3rd';
            case 3:
                return '4th';
            case 4:
                return '5th';
            case 5:
                return '6~7';
            case 6:
                return '8~9';
            case 7:
                return '10~11';
            case 8:
                return '12~16';
            case 9:
                return '17~21';
            case 10:
                return '22~26';
            case 11:
                return '27~36';
            case 12:
                return '37~46';
            case 13:
                return '47~56';
            case 14:
                return '57~81';
            case 15:
                return '82~106';
            case 16:
                return '107~156';
            case 17:
                return '157~206';
            case 18:
                return '207~306';
            case 19:
                return '307~406';
            case 20:
                return '407~506';
            case 21:
                return '507~806';
            case 22:
                return '807~1306';
            case 23:
                return '1307~2306';
        }
    }

}
