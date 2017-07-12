<?php

class MakeTab extends DB_conn{

    public $now_category;
    public $tab_html;
    public $tab_category = array();

    function get_tab_category() {
        return $this->tab_category;
    }

    function get_now_category() {
        return $this->now_category;
    }

    function set_now_category($category) {
        $this->now_category = $category;
    }

    function set_tab_category($var) {
        // 기존 TAB 배열에 있으면 두고 없음 추가
        $this->set_now_category($var);
        if (in_array($var, $this->tab_category) == false) {
            array_push($this->tab_category, $var);
            $this->tab_html .= <<< TAB
                <li class="sort" data-category="$var">$var</li>
TAB;
        }
    }
}
