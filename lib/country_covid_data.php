<?php

include 'country_list.php';

$all_countries_data = build_all_the_countries_data($countries);
// This is what we are after here
// a multidimensional associative array
// used in cases_cahrts.php -> function render_all_country_cases($all_countries_data) 

function build_all_the_countries_data($countries) {
    $all_countries_data = array();

    foreach ($countries as $country_name => $country_properties) {
        $page = country_url($country_properties['slug']);
        $all_countries_data[$country_name] = country_covid_data($page);
        $all_countries_data[$country_name]['population'] = $country_properties['population'];
        $all_countries_data[$country_name]['slug'] = $country_properties['slug'];
    }
    return ($all_countries_data);
    // New Zealand Data is being populated
}

function country_url($slug) {
    return "https://www.worldometers.info/coronavirus/country/{$slug}/";
 }

function country_covid_data($page) {
    
    $all_lines = file($page); // variable is an array
    
    $date_line_replace_this = ["/null/", "/\[/", "/\]/", "/\{/", "/\},/", "/\",\"/", "/\"/"];
    $date_line_with_this = [0, "", "", "", "", "§", ""];
    
    $number_line_replace_this = ["/null/", "/\[/", "/\]/", "/\{/", "/\},/", "/,/", "/\"/"];
    $number_line_with_this = [0, "", "", "", "", "§", ""];
    
    $case_marker = 0;
    $country_data_arr = [];
    
    foreach ($all_lines as $line)
    {
        if (strpos($line, "Highcharts.chart('graph-cases-daily'") !== false) {
            ++$case_marker; // to 1
            continue;
        }
        if (strpos($line, "xAxis: {") !== false && $case_marker == 1) {
            ++$case_marker; // to 2
            continue;
        }
        
        if (strpos($line, "categories: ") !== false && $case_marker == 2) {
            ++$case_marker; // to 3
            $editedLine = preg_replace($date_line_replace_this, $date_line_with_this, $line);
            $country_data_arr["dates"] = explode('§', str_replace("categories: ", "", trim($editedLine)));
            continue;
        }
        
        if (strpos($line, "name: 'Daily Cases'") !== false && $case_marker == 3) {
            ++$case_marker; // to 4
            continue;
        }
        
        if (strpos($line, "data: ") !== false && $case_marker == 4) {
            ++$case_marker; // to 5
            $editedLine = preg_replace($number_line_replace_this, $number_line_with_this, $line);
            $country_data_arr["cases"] = array_map('intval', explode('§', str_replace("data: ", "", trim($editedLine))));
            continue;
        }
        
        if (strpos($line, "Highcharts.chart('graph-deaths-daily'") !== false) {
            ++$case_marker; // to 6
            continue;
        }
        
        if (strpos($line, "name: 'Daily Deaths'") !== false && $case_marker == 6) {
            ++$case_marker; // to 7
            continue;
        }
        
        if (strpos($line, "data: ") !== false && $case_marker == 7) {
            $editedLine = preg_replace($number_line_replace_this, $number_line_with_this, $line);
            $country_data_arr["deaths"] = array_map('intval', explode('§', str_replace("data: ", "", trim($editedLine))));
            $case_marker = 0;
        }
        
        
        if (count($country_data_arr) > 0 &&  $case_marker === 0) {
            break;
        }
    };
    
    return ($country_data_arr);
    
};
