<?php

class BuildCountryTable {
    
    function __construct($country_array, $ignore_list, $healthcare_rankings){
        $ts = date("H:i:s");
        echo "BuildCountryTable __construct() is Called: $ts<br><br>";

        $this->country_array = $country_array;
        $this->ignore_list = $ignore_list;
        $this->healthcare_rankings = $healthcare_rankings;
        $this->countries_in_table = [];
        $this->log_mssg = '';
        
        // GlobalVariables
        $this->db_details = GlobalVariables::$db_details_live;
        $this->asymptomaticRate = GlobalVariables::$asymptomaticRate;

        $this->test_connection_to_db($this->db_details);

        $this->create_db($this->db_details);

        $this->create_country_table($this->db_details);

        $this->populate_country_table($this->db_details,$this->country_array);
        
    }

    private function test_connection_to_db($details) {
        $conn = new mysqli($details['servername'], $details['username'], $details['password']);

        // Check connection
        if ($conn->connect_error) {
            die('Error in connecting to the database');
        }

    }

    private function create_db($details) {
        $conn = new mysqli($details['servername'], $details['username'], $details['password']);

        // Create DB
        $sql = "CREATE DATABASE IF NOT EXISTS {$details['db_name']}";
        if ($conn->query($sql) === FALSE) {
            $this->log_mssg .= "\n¡Error! CREATE DATABASE IF NOT EXISTS '{$details['db_name']}' :: " . $conn->error . "\n";
            $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
        }

    }

    private function create_country_table($details) {
        $conn = new mysqli($details['servername'], $details['username'], $details['password'], $details['db_name']);

        $sql_01 = "DROP TABLE IF EXISTS Country";

        if ($conn->query($sql_01) === FALSE) {
            $this->log_mssg .= "\n¡Error! DROP TABLE 'Country' :: " . $conn->error . "\n";
            $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
        }

        // sql to create table
        $sql_02 = "CREATE TABLE Country (
            ID INT(32) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(100) NOT NULL,
            Slug VARCHAR(100) NOT NULL,
            HtmlId VARCHAR(100) NOT NULL,
            TotalCases int(32),
            TotalDeaths int(32),
            Population int(32),
            TestingIvermectin BOOLEAN,
            DistributionIvermectin BOOLEAN,
            Hemisphere VARCHAR(100),
            Continent VARCHAR(100),
            Region VARCHAR(100),
            GDP INT(32) UNSIGNED,
            HealthcareEfficiencyRank INT(6),
            CummulativeMortalityCases DECIMAL(5,2),
            CummulativeMortalityCasesAdj DECIMAL(5,2)
            )";
        
        if ($conn->query($sql_02) === FALSE) {
            $this->log_mssg .= "\n¡Error! CREATE TABLE 'Country' :: " . $conn->error . "\n";
            $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
        }
        
    }
    
    private function populate_country_table($details, $country_array) {

        $conn = new mysqli($details['servername'], $details['username'], $details['password'], $details['db_name']);
        
        
        $sql_01 = "TRUNCATE TABLE Country";
        if ($conn->query($sql_01) === FALSE) {
            $this->log_mssg .= "\n¡Error! TRUNCATE TABLE 'Country' :: " . $conn->error . "\n";
            $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
        }

        foreach($country_array as $country_name => $meta_array) {
            if ($this->country_checker($country_name, $meta_array) === TRUE ) {

                $cummulative_mortality_cases = round(($meta_array['TotalDeaths']/$meta_array['TotalCases'])*100,2);
                $cummulative_mortality_cases_adj = round(($meta_array['TotalDeaths']/($meta_array['TotalCases']*$this->asymptomaticRate))*100,2);

                $meta_array['HealthcareEfficiencyRank'] = $this->healthcare_rankings[$country_name];

                $sql_02 = "
                    INSERT INTO Country (Name, Slug, HtmlId, TotalCases, TotalDeaths, Population, HealthcareEfficiencyRank, CummulativeMortalityCases, CummulativeMortalityCasesAdj) 

                    VALUES ('$country_name', '{$meta_array['Slug']}', '{$meta_array['HtmlId']}', '{$meta_array['TotalCases']}', '{$meta_array['TotalDeaths']}', {$meta_array['Population']}, '{$meta_array['HealthcareEfficiencyRank']}',$cummulative_mortality_cases, $cummulative_mortality_cases_adj)
                ";
                
                if ($conn->query($sql_02) === FALSE) {
                    print_r("\n\n\nSQL BUG - SQL BUG - SQL BUG \n\n\n");
                    $this->log_mssg .= "\n¡Error! INSERT INTO 'Country' TABLE: " . $conn->error . "\n['$country_name'] => [\n";
                    foreach($meta_array as $key => $value){$this->log_mssg .= "\t['$key'] => $value ( ". gettype($value) . " )\n";}
                    $this->log_mssg .= "]\n";
                    $this->log_mssg .= "- - - - - - - - - - - - - - -\n";
                } else {
                    $last_id = $conn->insert_id;
                    $meta_array['CountryId'] = $last_id;
                }
                $this->countries_in_table[$country_name] = $meta_array;
            }
        }
        
        $this->log_mssg ? $this->log_mssg .= "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n" : null ;
        $this->log_mssg ? error_log($this->log_mssg) : null ;

    }

    private function country_checker($country_name, $meta_array) {
        $temp = TRUE;
        if ((array_search($country_name, $this->ignore_list) > -1) || ($meta_array['Population'] < 0) == true) {
            $temp = FALSE;
        }
        return $temp;
    }

    public function get_country_array() {
        return $this->countries_in_table;
    }

}
