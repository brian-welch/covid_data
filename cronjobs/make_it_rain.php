<?php
function myLoad($class) {
    
    foreach(['queries', 'componants', 'elements', 'forms', 'base', 'functions'] as $prefix) {
        if(file_exists("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php")) {
            include("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php");
        }
    }
}

spl_autoload_register('myLoad');

// Import some given arrays
include './healthcare_rankings.php';
include './ignore_list.php';
echo "<h6 style='font-family:font-size:30px;font-family:sans-serif;'>Began: " . date("H:i:s") . "</h6>";

$raw_country_array = new CountryListParser();

$country_table_builder = new BuildCountryTable($raw_country_array->get_country_array(), $ignore_list, $healthcare_rankings);

new BuildDailyDataTable($country_table_builder->get_country_array());

echo "<h6 style='font-family:font-size:30px;font-family:sans-serif;'>Ended: " . date("H:i:s") . "</h6>";

