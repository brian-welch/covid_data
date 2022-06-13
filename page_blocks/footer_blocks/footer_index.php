<?php

include 'block_one.php';
include 'block_two.php';
include 'block_three.php';

$date = GlobalVariables::todaysDate();

$footer = <<<FOOTER
<div id="footerContainer" class="container">
    <div class="row">
    <!--block_one-->
    $block_two
    $block_three
    </div>
    <div class="row">
        <div class="copyright-block">
            <span class="copyleft-span">Copy</span><span class="copyright-span">& Copyright</span>
            <span class="d-none d-sm-inline"> | </span><span class="d-xs-inline d-sm-none"><br></span> $date
            <span class="d-none d-sm-inline"> | </span><span class="d-xs-inline d-sm-none"><br></span> <a href="https://brianwelch.se/" target="new">Brian Christopher Welch</a>
        </div>
    </div> 
</div> <!-- end container -->

    <div class="footer-menu-container d-block d-sm-none" id="" style="">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid header">
                <button id="filterMenuButton_2" class="navbar-toggler filter-menu-button-outer menuClickable hideMe" type="button" data-bs-toggle="collapse" data-bs-target="#filterMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">
                    <span class="filter-menu-button-inner">
                        <span class="rotate_180"><i class="fas fa-sort-amount-up-alt"></i></span>
                            <i class="fas fa-sort-amount-up"></i>
                    </span>
                </button>


                <button class="navbar-toggler hamburger-menu-button-outer menuClickable" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">


                    <span class="hamburger-menu-button-inner" >
                        <hr><hr><hr>
                    </span>
                </button>
            </div>
        </nav>
    </div>
    <script src="/covid_data/js/after.js?{$add_file_suffix}"></script>
    </body></html>

FOOTER;

new Tag("hr","",[],true);

echo $footer;
