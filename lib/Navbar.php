<?php

class Navbar {
    
    public $pages;
    public $classes;
    public $id;
    public $designator;

    function __construct($designator) {
        global $pages;
        $this->pages = $pages;
        $this->classes = ["menu-list"];
        $this->id = "{$designator}_menu";
        $this->designator = $designator;
    }

    private function get_classes() {
        $temp = "class=\"";
        foreach ($this->classes as $index => $class) {
            $temp .= "{$class} ";
        }
        $temp .= "\"";
        return $temp;
    }

    public function add_classes($array) {
        $this->classes = array_merge($this->classes, $array);
    }

    public function add_class($string) {
        $this->classes[] = $string;
    }

    private function get_menu_items() {
        $temp = "<div {$this->get_classes()} id=\"{$this->id}\">";
        foreach ($this->pages as $index => $page) {
            $temp .= "<li class=\"d-inline-block\">{$page}</li>";
        }
        $temp .= "</div>";
        return $temp;
    }

    public function get_html() {
        return $this->get_menu_items();
    }

    function __destruct() {
    }
}
