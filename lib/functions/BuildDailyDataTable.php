<?php

class BuildDailyDataTable {
    
    function __construct($country_array) {
        $ts = date("H:i:s");
        echo "BuildDailyDataTable __construct() is Called: $ts<br><br>";

        $this->all_countries_data = [];
        $this->country_rows = [];
        $this->log_mssg = '';
        
        // GLOBAL VARIABLES
        $this->db_details = [
            'servername' => '127.0.0.1',
            'username' => 'root',
            'password' => 'Kanuffen1234@',
            'db_name' => 'CovidDataByCountry01',        
        ];
        $this->asymptomaticRate = 1.326;

        $this->test_connection_to_db($this->db_details);
        
        $this->create_daily_data_table($this->db_details);
        
        $this->parse_country_data($this->db_details,$country_array);

        $this->populate_daily_data_table($this->db_details, $this->all_countries_data);

        // $this->parse_then_populate($this->db_details,$country_array);

    }

    // private function parse_then_populate($details,$country_array) {
    //     $conn = new mysqli($details['servername'], $details['username'], $details['password'], $details['db_name']);

    //     $truncate = "TRUNCATE TABLE DailyData";

    //     if ($conn->query($truncate) === FALSE) {
    //         $this->log_mssg .= "\n¡Error! TRUNCATE TABLE DailyData: " . $conn->error . "\n['$country_id'] => [\n";
    //         $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
    //     }

    //     foreach($country_array as $country_name => $meta_array) {
    //         $this->parse_country_data($details, $country_name, $meta_array);
    //         $this->populate_daily_data_table($details, $this->all_countries_data);
    //     }
    // }

    private function parse_country_data($details, $country_array) { //$country_array) {
        $ts = date("H:i:s");
        echo "function parse_country_data() is Called: $ts<br><br>";
        // print_r("Number of Countries to Parse: ");
        // print_r(($country_array));//  - this checks out ok: all 185 countries arrive here and it's an array
        // print_r("\n\n");

        $this->all_countries_data = [];
        // $default_max_execution_time = ini_get('max_execution_time');
        // set_time_limit(240);

        foreach ($country_array as $country_name => $meta_array) {
            //print_r("Start: $country_name . . . . ");
            $all_lines = file("https://www.worldometers.info/coronavirus/country/{$meta_array['Slug']}/");          
            $date_line_replace_this = ["/null/", "/\[/", "/\]/", "/\{/", "/\},/", "/\",\"/", "/\"/", "/,/", "/Jan/", "/Feb/", "/Mar/", "/Apr/", "/May/", "/Jun/", "/Jul/", "/Aug/", "/Sep/", "/Oct/", "/Nov/", "/Dec/"];
            $date_line_with_this = [0, "", "", "", "", "§", "", "", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            
            $number_line_replace_this = ["/null/", "/\[/", "/\]/", "/\{/", "/\},/", "/,/", "/\"/"];
            $number_line_with_this = [0, "", "", "", "", "§", ""];
            
            $line_marker = 0;
            $country_data_arr = [];
            
            $country_population = $meta_array['Population'];
            $country_id = $meta_array['CountryId'];

            while (count($country_data_arr) === 0) {
                
                foreach ($all_lines as $index => $line) {

                    if (strpos($line, "Highcharts.chart('graph-cases-daily'") !== false) {
                        // echo $country_name . " - Highcharts.chart('graph-cases-daily' - found<br>";
                        ++$line_marker; // to 1
                        continue;
                    }

                    if (strpos($line, "xAxis: {") !== false && $line_marker == 1) {
                        // echo $country_name . " - Highcharts.chart('graph-cases-daily' / xAxis: { - found<br>";
                        ++$line_marker; // to 2
                        continue;
                    }
                    
                    if (strpos($line, "categories: ") !== false && $line_marker == 2) {
                        // echo $country_name . " - Highcharts.chart('graph-cases-daily' / categories:  - found<br>";
                        ++$line_marker; // to 3
                        $editedLine = preg_replace($date_line_replace_this, $date_line_with_this, $line);
    
                        $country_data_arr["datapoint_dates"] = array_map(function($date_array){
                            return $this->array_flipper($date_array);
                            }, explode('§', str_replace(" ", "-", str_replace("categories: ", "", trim($editedLine)))));
                        
                        continue;
                    }
                    
                    if (strpos($line, "name: 'Daily Cases'") !== false && $line_marker == 3) {
                        // echo $country_name . " - Highcharts.chart('graph-cases-daily' / name: 'Daily Cases' - found<br>";
                        ++$line_marker; // to 4
                        continue;
                    }
                    
                    if (strpos($line, "data: ") !== false && $line_marker == 4) {
                        // echo $country_name . " - Highcharts.chart('graph-cases-daily' / name: 'Daily Cases' / data:  - found<br>";
                        ++$line_marker; // to 5
                        $editedLine = preg_replace($number_line_replace_this, $number_line_with_this, $line);
                        $country_data_arr["cases_raw"] = array_map('intval', explode('§', str_replace("data: ", "", trim($editedLine))));
    
                        $country_data_arr['cases_adj'] = array_map(function ($cases_raw) {
                            return $this->case_adjuster($cases_raw);
                            }, $country_data_arr["cases_raw"]);
    
                        $country_data_arr['cases_adj_15_day'] = $this->fifteen_day_avg($country_data_arr['cases_adj']);
    
                        $country_data_arr['cases_adj_15_day_1M'] = array_map(function($case) use ($country_population){
                            return $this->per_million($case, $country_population);
                            }, $country_data_arr["cases_adj_15_day"]);
    
                        continue;
                    }
                    
                    if (strpos($line, "Highcharts.chart('graph-deaths-daily'") !== false) {
                        // echo $country_name . " - Highcharts.chart('graph-deaths-daily' - found<br>";
                        ++$line_marker; // to 6
                        continue;
                    }
                    
                    if (strpos($line, "name: 'Daily Deaths'") !== false && $line_marker == 6) {
                        // echo $country_name . " - Highcharts.chart('graph-cases-daily' / name: 'Daily Deaths' - found<br>";
                        ++$line_marker; // to 7
                        continue;
                    }
                    
                    if (strpos($line, "data: ") !== false && $line_marker == 7) {
                        // echo $country_name . " - Highcharts.chart('graph-cases-daily' / name: 'Daily Deaths' / data: - found<br><br>";
                        $editedLine = preg_replace($number_line_replace_this, $number_line_with_this, $line);
                        $country_data_arr["deaths_raw"] = array_map('intval', explode('§', str_replace("data: ", "", trim($editedLine))));
                        
                        $country_data_arr["deaths_15_day"] = $this->fifteen_day_avg($country_data_arr['deaths_raw']);
    
                        $country_data_arr['deaths_15_day_1M'] = array_map(function($deaths) use ($country_population){
                            return $this->per_million($deaths, $country_population);
                            }, $country_data_arr["deaths_15_day"]);
    
                        $country_data_arr["cases_adj_15_day_mortality"] = $this->cases_adj_15_day_mortality($country_data_arr['cases_adj_15_day'],$country_data_arr["deaths_15_day"]);
    
                        $line_marker = 0;
                    }
                    
                    if (count($country_data_arr) > 0 &&  $line_marker === 0) {
                        break;
                    }
    
                }  // END foreach line iteration through all lines
                            
            } // END While condition loop

            // echo "$country_name array key count: " . count(array_keys($country_data_arr)) . "<br>";
            
            if(!isset($country_data_arr["deaths_raw"])) {
                // add zeros if no deaths are reported on site
                $country_data_arr["deaths_raw"] = [];
                $country_data_arr["deaths_15_day"] = [];
                $country_data_arr["deaths_15_day_1M"] = [];
                $country_data_arr["cases_adj_15_day_mortality"] = [];
                for ($i = 0; $i < count($country_data_arr["datapoint_dates"]); $i++){
                    $country_data_arr["deaths_raw"][] += 0;
                    $country_data_arr["deaths_15_day"][] += 0;
                    $country_data_arr["deaths_15_day_1M"][] += 0;
                    $country_data_arr["cases_adj_15_day_mortality"][] += 0.00;
                }
            }
            
            $country_data_arr['cummulative_natural_immunity'] = $this->cummulative_natural_immunity($country_data_arr['cases_adj'],$country_data_arr["deaths_15_day"]);
            
            $this->all_countries_data[$country_id] = $country_data_arr;              

        } // End each countries foreach loop
        
        // set_time_limit($default_max_execution_time);
        /// print_r("DONE! \n\n");

        // usleep(intval(substr(round(rand()*.01),0,7)));
        sleep(1);
    } // Parser ends here

    private function populate_daily_data_table($details, $country_data) {

        $conn = new mysqli($details['servername'], $details['username'], $details['password'], $details['db_name']);

        $truncate = "TRUNCATE TABLE DailyData";

        if ($conn->query($truncate) === FALSE) {
            $this->log_mssg .= "\n¡Error! TRUNCATE TABLE DailyData: " . $conn->error . "\n['$country_id'] => [\n";
            $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
        }



        foreach($country_data as $country_id => $daily_data) {
            $datapoint_dates                = $daily_data['datapoint_dates'];
            $cases_raw                      = $daily_data['cases_raw'];
            $cases_adj                      = $daily_data['cases_adj'];
            $cases_adj_15_day               = $daily_data['cases_adj_15_day'];
            $cases_adj_15_day_1M            = $daily_data['cases_adj_15_day_1M'];
            $deaths_raw                     = $daily_data['deaths_raw'];
            $deaths_15_day                  = $daily_data['deaths_15_day'];
            $deaths_15_day_1M               = $daily_data['deaths_15_day_1M'];
            $cases_adj_15_day_mortality     = $daily_data['cases_adj_15_day_mortality'];
            $cummulative_natural_immunity   = $daily_data['cummulative_natural_immunity'];

            foreach ($datapoint_dates as $index => $value ) {
                $sql =  "INSERT INTO DailyData (
                            CountryID,
                            DatapointDate,
                            MostRecent,
                            CasesRaw,
                            CasesAdj,
                            CasesAdj15Day,
                            CasesAdj15Day1M,
                            DeathsRaw,
                            Deaths15Day,
                            Deaths15Day1M,
                            MortalityVsCases,
                            CummulativeNaturalImmunity
                        )
                        VALUES (
                            $country_id,
                            $value,
                            TRUE,
                            {$cases_raw[$index]},
                            {$cases_adj[$index]},
                            {$cases_adj_15_day[$index]},
                            {$cases_adj_15_day_1M[$index]},
                            {$deaths_raw[$index]},
                            {$deaths_15_day[$index]},
                            {$deaths_15_day_1M[$index]},
                            {$cases_adj_15_day_mortality[$index]},
                            {$cummulative_natural_immunity[$index]}
                        )";
                
                if ($conn->query($sql) === FALSE) {
                    $this->log_mssg .= "\n¡Error! INSERT INTO 'DailyData' TABLE: " . $conn->error . "\n['$country_id'] => [\n";
                    $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
                }

            }

        }
        
        $this->log_mssg ? $this->log_mssg .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n" : null ;
        $this->log_mssg ? error_log($this->log_mssg) : null ;

    } // Populate table ends here


    private function create_daily_data_table($details){
        $conn = new mysqli($details['servername'], $details['username'], $details['password'], $details['db_name']);

        // sql to create table
        $sql_01 = "CREATE TABLE IF NOT EXISTS DailyData (
            ID INT(32) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            CountryID INT(32) NOT NULL,
            Timestamp TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6),
            DatapointDate VARCHAR(32) NOT NULL,
            MostRecent BOOLEAN,
            CasesRaw INT(9) NOT NULL,
            CasesAdj INT(9) NOT NULL,
            CasesAdj15Day INT(9) NOT NULL,
            CasesAdj15Day1M INT(9) NOT NULL,
            DeathsRaw INT(9),
            Deaths15Day INT(9),
            Deaths15Day1M INT(9),
            MortalityVsCases DECIMAL(5,2),
            CummulativeNaturalImmunity INT(12) NOT NULL
            )";
        
        if ($conn->query($sql_01) === FALSE) {
            die('¡Error! CREATE TABLE \'DailyData\' -> ' . $conn->error );

        }

    }

    private function test_connection_to_db($details) {
        $conn = new mysqli($details['servername'], $details['username'], $details['password'], $details['db_name']);

        // Check connection
        if ($conn->connect_error) {
            die('Error in connecting to the database');
        }

    }
    


    /*   PARSER SUB - FUNCTIONS   */

    private function array_flipper($date) {
        $temp_arr = explode("-", $date);
        $temp_arr = [$temp_arr[2], $temp_arr[0], $temp_arr[1]];        
        return '"' . implode("-", $temp_arr) . '"';
    }

    private function case_adjuster($case) {
        return intval(round($case * $this->asymptomaticRate));
    }
    
    private function per_million($number, $population){
        return intval(round(($number * 1000000)/$population));
    }

    private function fifteen_day_avg($number_array) {
        $temp = [];
        foreach($number_array as $index => $number){
            if (($index < 7) || ($index > (count($number_array) - 8))) {
                $temp[] += null;
            } else {
                 $temp[] += intval(round(array_sum(array_slice($number_array,($index-7),15))/15));
            }
        }
        return $temp;
    }

    private function cases_adj_15_day_mortality($case_array, $death_array) {
        $temp = [];
        foreach($death_array as $index => $number){
            if ( ($index < 19) || ($index > (count($death_array) - 8))) {
                $temp[] += null;
            } else {
                $percent = $case_array[$index-19] === 0 ? null : (round(  ($number / $case_array[$index-19])*100 , 2));
                $percent = $percent > 100 ? 100 : $percent;
                $temp[] += $percent;
            }
        }
        return $temp;
    }

    private function cummulative_natural_immunity($case_array, $death_array){
        $temp = [];
        foreach($case_array as $index => $number){
            $net = intval(array_sum(array_slice($case_array, 0, $index))) - intval(array_sum(array_slice($death_array, 0, $index)));
            $temp[] += $net;
        }
        return $temp;
    }

    private function get_sleep() {
        $x = intval(substr(round(rand()*.01),0,7));
        return $x;
        
    }

}