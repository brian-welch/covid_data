<?php

class SiteName {

    private $site_title;
    
    function __construct ($site_title_ext = "Graphing COVID: 1.0", $attrs = []) {
        $this->site_title = $site_title_ext;
        $this->attrs = $attrs;
        $this->set_attribute('class','site-name');
        $this->set_attribute('id','siteName');
    }
    
    public function set_attribute($attr, $value) {
        array_key_exists($attr, $this->attrs) ? $this->attrs[$attr] .= " " . $value : $this->attrs[$attr] = $value;
    }

    public function get_html() {
        $temp = "<div";
        foreach($this->attrs as $attr => $value) {
          $temp .= " {$attr}='{$value}'";
        }
        $temp .= "><a href='/'>{$this->site_title}</a></div>";
        return  $temp;
    }
}
