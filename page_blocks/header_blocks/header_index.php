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
        <link rel="stylesheet" href="/covid_data/css/style.css?{$add_file_suffix}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js" integrity="sha512-b3xr4frvDIeyC3gqR1/iOi6T+m3pLlQyXNuvn5FiRrrKiMUJK3du2QqZbCywH6JxS5EOfW0DY0M6WwdXFbCBLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <script src="/covid_data/js/before.js?{$add_file_suffix}"></script>
    </head>
    <body>
    <div class="navbar-center bg-dark">
        <div class="navbar-title-container" id="" style="">

            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid header">
                    {$site_name->get_html()}
                    <div id="" class="button_container">

                        <button class="navbar-toggler filter-menu-button-outer d-none d-sm-block menuClickable hideMe" type="button">
                            <a href="/covid_data/usa/">The 'States'</a>
                        </button>

                        <button id="filterMenuButton" class="navbar-toggler filter-menu-button-outer d-none d-sm-block menuClickable hideMe" type="button" data-bs-toggle="collapse" data-bs-target="#filterMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">
                            <span class="filter-menu-button-inner" >
                            <span class="rotate_180"><i class="fas fa-sort-amount-up-alt"></i></span>
                                <i class="fas fa-sort-amount-up"></i>
                            </span>
                        </button>

                        <button class="navbar-toggler hamburger-menu-button-outer d-none d-sm-block menuClickable" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">
                            <span class="hamburger-menu-button-inner" >
                                <hr><hr><hr>
                            </span>
                        </button>
                    </div>
                </div>
            </nav>

            <div class="collapse menuSort" id="navbarMainMenu">
                <div class="bg-dark main-menu">
                    <ul>
                        <hr>
                        <li class="main-menu-button menuClickable" id="casesPerMillion" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation"><a href="#">Cases Per Million</a></li>
                        <hr>
                        <li class="main-menu-button menuClickable" id="deathsPerMillion" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation"><a href="#">Deaths Per Million</a></li>
                        <hr>
                        <li class="main-menu-button menuClickable" id="mortalityRateByCases" data-bs-toggle="collapse" data-bs-target="#navbarMainMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation"><a href="#">Mortality Rate By Cases</a></li>
                    </ul>
                </div>
            </div>

            <div class="collapse menuSort" id="filterMenu">
                <div class="bg-dark filter-menu-inner">
                    <hr>
                    <div id="" class="sort-filter-section">
                        <h5>Sort Graphs By:</h5>
                        <button id="" class="sort-button" data-sort-direction="asc" data-sort-param="countryName">Country Name</button>
                        <button id="" class="sort-button" data-sort-direction="asc" data-sort-param="population">Population</button>
                        <button id="" class="sort-button" data-sort-direction="asc" data-sort-param="healthcareEfficiency">Heathcare Efficiency</button>
                        <button id="" class="sort-button" data-sort-direction="asc" data-sort-param="highestPeak">Graph Peaks</button>
                        <button id="" class="sort-button" data-sort-direction="asc" data-sort-param="sumCummulativeData">Graph Area Sum</button>
                    </div>
                    <div id="sortingText"></div>
                    <hr>
                    <div id="" class="">
                        <h5>County Selector:</h5>
                        <div id="" class="country-filter-thumbnail"><img src="/covid_data/images/example_graph_01.jpg" /></div>
                        
                        <div id="" class="country-filter-message hideMe" data-filter-param="country">
                            <span>Coutries Selected: Clicking 'Apply' will filter to only the selected countries</span>
                            <button class="deselect-countries-button">Deselect All Countries</button>
                        </div>
                    </div>
                    <div class="bottom-buttons">
                        <button id="applyFilters" class="apply-filter-menu-button" type="button" data-bs-toggle="collapse" data-bs-target="#filterMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">
                            <a href="#">Apply</a>
                        </button>
                        <button id="closefilters" class="apply-filter-menu-button" type="button" data-bs-toggle="collapse" data-bs-target="#filterMenu" aria-controls="navbarMainMenu" aria-expanded="false" aria-label="Toggle navigation" onclick="this.blur()">
                            <a href="#">Cancel</a>
                        </button>
                    </div>
                </div>
            </div>            

        </div>
    </div>
HEADER;

echo $block;

