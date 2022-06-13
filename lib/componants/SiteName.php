<?php

class SiteName {

    private $site_title;
    
    function __construct ($site_title_ext = "Graphing COVID: 2.0", $attrs = []) {
        $this->site_title = $site_title_ext;
        $this->attrs = $attrs;
        $this->set_attribute('class','site-name');
        $this->set_attribute('id','siteName');
    }
    
    public function set_attribute($attr, $value) {
        isset($this->attrs[$attr]) ? $this->attrs[$attr] .= " " . $value : $this->attrs[$attr] = $value;
    }

    public function get_html() {
        $temp = "<div";
        foreach($this->attrs as $attr => $value) {
          $temp .= " {$attr}='{$value}'";
        }
        $temp .= "><a href='/covid_data/'>{$this->site_title}</a></div>";
        return  $temp;
    }
}
