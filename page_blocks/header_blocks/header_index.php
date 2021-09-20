<?php

$base = new Base();
$base->set_site_subtitle('COVID Data Visualized with Relative and Comparative Scales');

$title_tag = new Tag("title", $base->site_title);

$site_name = new SiteName();

$navbar = new Navbar('main');

$add_file_suffix = date("YmdHis");

$block = <<<HEADER
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        {$title_tag->get_html()}
        <meta name="description" content="An amateur project with the intent to present statistical data related to infections and death.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/style.css?{$add_file_suffix}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js" integrity="sha512-b3xr4frvDIeyC3gqR1/iOi6T+m3pLlQyXNuvn5FiRrrKiMUJK3du2QqZbCywH6JxS5EOfW0DY0M6WwdXFbCBLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="js/before.js?{$add_file_suffix}"></script>
    </head>
    <body>
    <div class="navbar-center bg-dark">
        <div class="navbar-title-container" id="" style="">

            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid header">
                {$site_name->get_html()}
                    <button class="navbar-toggler hamburger-menu-outer d-none d-sm-block" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">
                        <span class="hamburger-menu-inner" >
                            <hr><hr><hr>
                        </span>
                    </button>
                </div>
            </nav>

            <div class="collapse" id="navbarMainMenu">
                <div class="bg-dark main-menu">
                    <ul>
                        <hr>
                        <li class="main-menu_button" id="casesPerMillion" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation"><a href="#">Cases Per Million</a></li>
                        <hr>
                        <li class="main-menu_button" id="deathsPerMillion" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation"><a href="#">Deaths Per Million</a></li>
                        <hr>
                        <li class="main-menu_button" id="mortalityRateByCases" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation"><a href="#">Mortality Rate By Cases</a></li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
HEADER;

echo $block;
