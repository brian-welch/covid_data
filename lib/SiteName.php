<?php

class SiteName {

    private $site_title;
    
    function __construct ($site_title_ext = "COVID Data Visualized", $attrs = []) {
        $this->site_title = $site_title_ext;
        $this->attrs = $attrs;
        $this->set_attribute('class','site-name');
        $this->set_attribute('id','siteName');
    }
    
    public function set_attribute($attr, $value) {
        $this->attrs[$attr] ? $this->attrs[$attr] .= " " . $value : $this->attrs[$attr] = $value;
    }

    public function get_html() {
        $temp = "<div";
        foreach($this->attrs as $attr => $value) {
          $temp .= " {$attr}='{$value}'";
        }
        $temp .= ">{$this->site_title}</div>";
        return  $temp;
    }

    public function echo_html() {
        echo $this->get_html();
    }
}
