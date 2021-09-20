<?php

$all_lines_home_page = file("https://www.worldometers.info/coronavirus/");


function getPopulation($slug) {
    //returns population in integer type
    $population = 0;
    $find_this = '<td style="font-weight: bold; text-align:right"><a href="/world-population/';
    // $all_lines = file("https://www.worldometers.info/coronavirus/"); // variable is an array
    global $all_lines_home_page;
    foreach ( $all_lines_home_page as $line)
    {
        if (strpos($line, $find_this . "{$slug}-population/") !== false) {
            $population = intval(str_replace( ",", "", str_replace( $find_this . "{$slug}-population/" . "\">", '', $line)));
        }
    }
    return $population;
}

$countries = array(
    "USA" => array(
        "slug" => "us",
        "population" => getPopulation("us"),
    ),
    "UK" => array(
        "slug" => "uk",
        "population" => getPopulation("uk"),
    ),
    "Slovakia" => array(
        "slug" => "slovakia",
        "population" => getPopulation("slovakia"),
    ),
    "Czechia" => array(
        "slug" => "czech-republic",
        "population" => getPopulation("czech-republic"),
    ),
    // "Argentina" => array(
    //     "slug" => "argentina",
    //     "population" => getPopulation("argentina"),
    // ),
    // "Peru" => array(
    //     "slug" => "peru",
    //     "population" => getPopulation("peru"),
    // ),
    // "Brazil" => array(
    //     "slug" => "brazil",
    //     "population" => getPopulation("brazil"),
    // ),
    // "France" => array(
    //     "slug" => "france",
    //     "population" => getPopulation("france"),
    // ),
    // "Germany" => array(
    //     "slug" => "germany",
    //     "population" => getPopulation("germany"),
    // ),
    // "Canada" => array(
    //     "slug" => "canada",
    //     "population" => getPopulation("canada"),
    // ),
    // "Australia" => array(
    //     "slug" => "australia",
    //     "population" => getPopulation("australia"),
    // ),
    // "New Zealand" => array(
    //     "slug" => "new-zealand",
    //     "population" => 5002100,
    // ),
    // "Sweden" => array(
    //     "slug" => "sweden",
    //     "population" => getPopulation("sweden"),
    // ),
    // "India" => array(
    //     "slug" => "india",
    //     "population" => getPopulation("india"),
    // ),
);






