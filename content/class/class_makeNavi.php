<?php

class MakeNavi {

    public $total_content;
    public $limit_row;
    public $limit_navi;
    public $this_page;
    //
    public $total_navi;

    function __construct($total_content, $limit_row, $limit_navi, $this_page) {
        $this->total_content = $total_content;
        $this->limit_row = $limit_row;
        $this->limit_navi = $limit_navi;
        $this->this_page = $this_page;
        $this->total_navi_count();
    }

    // 전체 네비 갯수 리턴
    function total_navi_count() {
        $this->total_navi = ceil($this->total_content / $this->limit_row);
    }

    // 클릭한 페이지가 처음 혹은 끝인지 체크
    function chkNaviLocation() {

        $is['first'] = true;
        $is['last'] = true;

        // 현재 클릭된 페이지의 첫 페이지 가져오기
        $first_navi = $this->limit_navi * floor($this->this_page / $this->limit_navi);

        // 현재 클릭된 페이지의 첫 페이지 가져오기
        $last_navi = $first_navi + $this->limit_navi;

        // 현재 클릭한 페이지에 필요한 이전 다음 버튼은 무엇?
        if ($first_navi == 1) {
            $is['first'] = false;
        } else if ($first_navi * $this->limit_navi >= $this->total_navi) {
            $is['last'] = false;
        }

        $is['$first_navi'] = $first_navi;
        $is['$last_navi'] = $last_navi;

        return $is;
    }

    // 일반 페이지 UI
    function nomalNavi($num) {
        $page_num = $num + 1;
        if ($num == $this->this_page) {
            $active = 'active';
        } else {
            $active = '';
        }
        return '<li class="paging ' . $active . '" data-num-index="' . $page_num . '"><a style="cursor:pointer">' . $page_num . '</a></li>';
    }

    // 이전 페이지 UI
    function preNavi($num) {

        $navi_pre = '<li class="page_move" data-num-next="' . $num . '" style="cursor:pointer">';
        $navi_pre .= '<a aria-label="Previous">';
        $navi_pre .= '<span aria-hidden="true">&laquo;</span>';
        $navi_pre .= '</a>';
        $navi_pre .= '</li>';

        return $navi_pre;
    }

    // 다음 페이지 UI
    function nextNavi($num) {

        $navi_next = '<li class="page_move" data-num-next="' . $num . '" style="cursor:pointer">';
        $navi_next .= '<a aria-label="Next">';
        $navi_next .= '<span aria-hidden="true">&raquo;</span>';
        $navi_next .= '</a>';
        $navi_next .= '</li>';

        return $navi_next;
    }

    // 조립하기
    function makeNavi() {

        $is = $this->chkNaviLocation();

        if ($this->total_navi > $is['$last_navi']) {
            $roof = $is['$last_navi'];
        } else {
            $roof = $this->total_navi;
        }

        if ($is['$first_navi'] > 0) {
            $li = $this->preNavi($is['$first_navi']);
        }

        for ($i = $is['$first_navi']; $i < $roof; $i++) {
            $li .= $this->nomalNavi($i);
        }
        
        if ($is['$last_navi'] < $this->total_navi) {
            $li .= $this->nextNavi($is['$last_navi'] + 1);
        }

        return $li;
    }
}
