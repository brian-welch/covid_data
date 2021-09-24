<?php

class Tag {

    function __construct($tag_name, $tag_content = "", $attrs = [], $render_direct = false) {
        $this->tag_name = $tag_name;
        $this->tag_content = $tag_content;
        $this->attrs = $attrs;
        $this->render_direct = $render_direct;
    }

    public function set_attribute($attr, $value) {
        $this->attrs[$attr] ? $this->attrs[$attr] .= " {$value}" : $this->attrs[$attr] = "{$value}";
    }

    public function get_html() {
        $temp = "<{$this->tag_name}";
        if (($this->attrs))
            foreach($this->attrs as $attr => $value) {
                $temp .= " {$attr}='" . $value . "'";
            }
        $temp.= ">{$this->tag_content}</{$this->tag_name}>";
        return  $temp;
    }

    public function echo_html() {
        echo $this->get_html();
    }

    function __destruct() {
        $this->render_direct ? $this->echo_html() : null;
    }

}
