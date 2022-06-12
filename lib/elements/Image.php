<?php

class Image {

    // Submitted arguments
    public $src;
    private $attrs;

    function __construct($src, $alt = "", $width = '', $boolean = false) {
        $this->src = $src;
        $this->alt = $alt;
        $this->attrs = [];
        $this->width = $width;
        $this->boolean = $boolean;
    }

    public function set_attribute($attr, $value) {
        isset($this->attrs[$attr]) ? $this->attrs[$attr] .= " " . $value : $this->attrs[$attr] = $value;
    }

    public function get_html() {
        $temp = "<img src='{$this->src}' alt='{$this->alt}' width='{$this->width}' ";
        foreach($this->attrs as $attr => $value) {
            $temp .= " {$attr}={$value}";
        }
        $temp.= " />";
        return  $temp;
    }

    public function echo_html() {
        echo $this->get_html();
    }

    function __destruct() {
        $this->boolean ? $this->echo_html() : null;
    }

}
