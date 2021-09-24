<?php

include 'left_block.php';
include 'center_block.php';
include 'right_block.php';

$footer = <<<FOOTER
<div class="container">
    <div class="row">
        {$left_block}
        {$center_block}
        {$right_block}
    </div>
    <div class="row">
        <div class="copyright-block">
            <span class="copyleft-span">Copy</span><span class="copyright-span">& Copyright</span>
            <span class="d-none d-sm-inline"> | </span><span class="d-xs-inline d-sm-none"><br></span> {$base->dS_M_Y}
            <span class="d-none d-sm-inline"> | </span><span class="d-xs-inline d-sm-none"><br></span> Brian Christopher Welch
        </div>
    </div> 
</div> <!-- end container -->

    <div class="footer-menu-container d-block d-sm-none" id="" style="">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid header">
                <button class="navbar-toggler hamburger-menu-outer" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">
                    <span class="hamburger-menu-inner" >
                        <hr><hr><hr>
                    </span>
                </button>
            </div>
        </nav>
    </div>
    <script src="js/after.js?{$add_file_suffix}"></script>
    </body></html>

FOOTER;

new Tag("hr","","",true);

echo $footer;
