<?php

 class Base {

    public $Y_m_d;
    public $y_m_d;
    public $Y_M_d;
    public $dS_M_Y;
    public $site_title;

    function __construct() {
        $this->Y_m_d   =   date("Y - m - d");     // 2021 - 08 - 15
        $this->y_m_d   =   date("y - m - d");     // 21 - 08 - 15
        $this->Y_M_d   =   date("Y - M - d");     // 2021 - Aug - 15
        $this->jS_M_Y  =   date("jS \of F, Y");  // 15th of August, 2021
        $this->site_title     =   "Tutela Veritatis";
    }

    public function set_site_subtitle($string) {
        $this->site_title = $this->site_title .= ": " . $string;
    }

 }
