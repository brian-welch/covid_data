<?php

class BuildDailyDataArchive {
    function __construct(){
        $this->dir = __DIR__;
        // DB Signin Info
        $this->db_details = GlobalVariables::$db_details;

        $this->archive_exising_daily_data($this->db_details);
        
    }
    
    private function archive_exising_daily_data($db_details) {
        $date_array = explode('-',date("Y-m"));
        $path_and_filename = "{$this->dir}/archive/$date_array[0]/$date_array[1]/monthly_data_dump_$date_array[0]-$date_array[1].csv";

        if(file_exists($path_and_filename)) {
            die();
        }
        
        $conn = new mysqli($db_details['servername'], $db_details['username'], $db_details['password'], $db_details['db_name']);
        
        $this->make_dir($date_array);
        
        $sql = "
        SELECT * 
        /*INTO OUTFILE '$path_and_filename'
        FIELDS TERMINATED BY ','
        ENCLOSED BY '\"'
        LINES TERMINATED BY '\\n'*/
        FROM DailyData ORDER BY ID
        ";
        // OUTFILE didn't work on account of variable settings in mysql - and I didn't want to waste time working on that fix
        
        $data_string = '';
        $results = $conn->query($sql);
        
        while ($data_row = $results->fetch_assoc()) {
            $data_array[] = $data_row;
            $data_string .= implode(", ", $data_row) . "\n";
        }

        file_put_contents($path_and_filename, $data_string);
    }
    
    private function make_dir($date_array){

        if(!is_dir("{$this->dir}/archive/$date_array[0]")){
            mkdir("{$this->dir}/archive/$date_array[0]", 0755);
        }
        if(!is_dir("{$this->dir}/archive/$date_array[0]/$date_array[1]")){
            mkdir("{$this->dir}/archive/$date_array[0]/$date_array[1]", 0755);
        }
    }

    function __destruct(){
    }
}

new BuildDailyDataArchive();