<?php
function myLoad($class) {
    
    foreach(['queries', 'componants', 'elements', 'forms', 'base', 'functions'] as $prefix) {
        if(file_exists("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php")) {
            include_once("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php");
        }
    }
}
spl_autoload_register('myLoad');
//=============================================================================

class Queryrouter {

    function __construct($get_array) {

        $this->db_details = GlobalVariables::$db_details;
        $this->category = $get_array['category'];
        $this->country_ids = $get_array['country_ids'];
        // $this->count = $get_array['count'];
        // $this->id_range_start = $get_array['id_range_start'];
       
        switch ($this->category) {
            case 'casesPerMillion':
                $this->casesPerMillion($this->country_ids);
                break;
            case 'deathsPerMillion':
                $this->deathsPerMillion($this->country_ids);
                break;
            case 'mortalityRateByCases':
                $this->mortalityRateByCases($this->country_ids);
                break;
            default:
                die("Bug: Query Router > construct function");
          }

    }

    private function casesPerMillion($country_ids) {
        // $id_range_start = intval($id_range_start);
        // $count = intval($count);
        $where_01 = 'WHERE ';
        $where_02 = 'WHERE ';
        $column_name = "";
        $grouped_rows = [];
        $id_arr  = ($country_ids !== 'all' ? explode(",", $country_ids) : range(1, 185));

        foreach ($id_arr as $index => $id) {
            $where_01 .= ($index > 0 ? " OR " : "") . " ID = " . $id;
            $where_02 .= ($index > 0 ? " OR " : "") . " CountryID = " . $id;
        } 

        $result_01 = $this->get_country_results($where_01);
        $result_02 = $this->get_cases_adjusted_15day_per_million_results($where_02);

        if (count($result_01) === 0 || count($result_02) === 0){
            die("Bug: Query Router > construct function >  no result 01 or result 02");
        }

        foreach ($result_01 as $index => $arr) {
            $grouped_rows[intval($arr['ID'])]  = ['name'=>$arr['Name']];
            $grouped_rows[intval($arr['ID'])] += ['htmlId'=>$arr['HtmlId']];
            $grouped_rows[intval($arr['ID'])] += ['population'=>intval($arr['Population'])];
            $grouped_rows[intval($arr['ID'])] += ['healthcareEfficiencyRank'=>intval($arr['HealthcareEfficiencyRank'])];
            $grouped_rows[intval($arr['ID'])] += ['cummulativeMortalityCasesAdj'=>floatval($arr['CummulativeMortalityCasesAdj'])];
            $grouped_rows[intval($arr['ID'])] += ['datasetLabel'=>'Cases/M'];
            $grouped_rows[intval($arr['ID'])] += ['dates'=>[]];
            $grouped_rows[intval($arr['ID'])] += ['dataPoints'=>[]];
        }

        $yAxis_max = 0;
        foreach ($result_02 as $index => $arr) {
            $grouped_rows[intval($arr['CountryID'])]['dates'][] = '"' . $arr['DatapointDate'] . '"';
            $grouped_rows[intval($arr['CountryID'])]['dataPoints'][] = intval($arr['CasesAdj15Day1M']);
            $yAxis_max = (intval($arr['CasesAdj15Day1M']) > $yAxis_max ? intval($arr['CasesAdj15Day1M']) : $yAxis_max);
        }

        foreach($grouped_rows as $country_id => $data_array) {
            $slice_here = (count($grouped_rows[$country_id]['dates']) - 7);
            array_splice($grouped_rows[$country_id]['dates'], $slice_here);
            array_splice($grouped_rows[$country_id]['dataPoints'], $slice_here);
            $grouped_rows[$country_id]['yAxis_max'] = round($yAxis_max, -2) + 250;
        }

        echo json_encode($grouped_rows); //  . "\n\n";

    }

    private function deathsPerMillion($country_ids) {
        // $id_range_start = intval($id_range_start);
        // $count = intval($count);
        $where_01 = 'WHERE ';
        $where_02 = 'WHERE ';
        $column_name = "";
        $grouped_rows = [];
        $id_arr  = ($country_ids !== 'all' ? explode(",", $country_ids) : range(1, 185));

        foreach ($id_arr as $index => $id) {
            $where_01 .= ($index > 0 ? " OR " : "") . " ID = " . $id;
            $where_02 .= ($index > 0 ? " OR " : "") . " CountryID = " . $id;
        } 

        $result_01 = $this->get_country_results($where_01);
        $result_02 = $this->get_deaths_15day_per_million_results($where_02);

        if (count($result_01) === 0 || count($result_02) === 0){
            die("Bug: Query Router > deths per million function > no fesult 01 & result 02");
        }

        foreach ($result_01 as $index => $arr) {
            $grouped_rows[intval($arr['ID'])]  = ['name'=>$arr['Name']];
            $grouped_rows[intval($arr['ID'])] += ['htmlId'=>$arr['HtmlId']];
            $grouped_rows[intval($arr['ID'])] += ['population'=>intval($arr['Population'])];
            $grouped_rows[intval($arr['ID'])] += ['healthcareEfficiencyRank'=>intval($arr['HealthcareEfficiencyRank'])];
            $grouped_rows[intval($arr['ID'])] += ['cummulativeMortalityCasesAdj'=>floatval($arr['CummulativeMortalityCasesAdj'])];
            $grouped_rows[intval($arr['ID'])] += ['datasetLabel'=>'Deaths/M'];
            $grouped_rows[intval($arr['ID'])] += ['dates'=>[]];
            $grouped_rows[intval($arr['ID'])] += ['dataPoints'=>[]];
        }

        $yAxis_max = 0;
        foreach ($result_02 as $index => $arr) {
            $grouped_rows[intval($arr['CountryID'])]['dates'][] = '"' . $arr['DatapointDate'] . '"';
            $grouped_rows[intval($arr['CountryID'])]['dataPoints'][] = floatval($arr['Deaths15Day1M']);
            $yAxis_max = (floatval($arr['Deaths15Day1M']) > $yAxis_max ? floatval($arr['Deaths15Day1M']) : $yAxis_max);
        }

        foreach($grouped_rows as $country_id => $data_array) {
            $slice_here = (count($grouped_rows[$country_id]['dates']) - 7);
            array_splice($grouped_rows[$country_id]['dates'], $slice_here);
            array_splice($grouped_rows[$country_id]['dataPoints'], $slice_here);
            $grouped_rows[$country_id]['yAxis_max'] = round($yAxis_max, -1) + 2;
        }

        echo json_encode($grouped_rows);

    }

    private function mortalityRateByCases($country_ids) {
        // $id_range_start = intval($id_range_start);
        // $count = intval($count);
        $where_01 = 'WHERE ';
        $where_02 = 'WHERE ';
        $column_name = "";
        $grouped_rows = [];
        $id_arr  = ($country_ids !== 'all' ? explode(",", $country_ids) : range(1, 185));

        foreach ($id_arr as $index => $id) {
            $where_01 .= ($index > 0 ? " OR " : "") . " ID = " . $id;
            $where_02 .= ($index > 0 ? " OR " : "") . " CountryID = " . $id;
        } 

        $result_01 = $this->get_country_results($where_01);
        $result_02 = $this->get_mortalityRateByCases_results($where_02);

        if (count($result_01) === 0 || count($result_02) === 0){
            die("Bug: Query Router > mortality rate by cases function > no result 01 result 02");
        }

        foreach ($result_01 as $index => $arr) {
            $grouped_rows[intval($arr['ID'])]  = ['name'=>$arr['Name']];
            $grouped_rows[intval($arr['ID'])] += ['htmlId'=>$arr['HtmlId']];
            $grouped_rows[intval($arr['ID'])] += ['population'=>intval($arr['Population'])];
            $grouped_rows[intval($arr['ID'])] += ['healthcareEfficiencyRank'=>intval($arr['HealthcareEfficiencyRank'])];
            $grouped_rows[intval($arr['ID'])] += ['cummulativeMortalityCasesAdj'=>floatval($arr['CummulativeMortalityCasesAdj'])];
            $grouped_rows[intval($arr['ID'])] += ['datasetLabel'=>'%'];
            $grouped_rows[intval($arr['ID'])] += ['dates'=>[]];
            $grouped_rows[intval($arr['ID'])] += ['dataPoints'=>[]];
        }

        $yAxis_max = 9;
        foreach ($result_02 as $index => $arr) {
            $grouped_rows[intval($arr['CountryID'])]['dates'][] = '"' . $arr['DatapointDate'] . '"';
            $grouped_rows[intval($arr['CountryID'])]['dataPoints'][] = floatval($arr['MortalityVsCases']);
            //$yAxis_max = (intval($arr['MortalityVsCases']) > $yAxis_max ? intval($arr['MortalityVsCases']) : $yAxis_max);
        }

        foreach($grouped_rows as $country_id => $data_array) {
            $slice_here = (40);
            $grouped_rows[$country_id]['dates'] = array_splice($grouped_rows[$country_id]['dates'], $slice_here);
            $grouped_rows[$country_id]['dataPoints'] = array_splice($grouped_rows[$country_id]['dataPoints'], $slice_here);

            $and_slice_here = (count($grouped_rows[$country_id]['dates']) - 7);
            array_splice($grouped_rows[$country_id]['dates'], $and_slice_here);
            array_splice($grouped_rows[$country_id]['dataPoints'], $and_slice_here);

            $grouped_rows[$country_id]['yAxis_max'] = ($yAxis_max) + 1;
        }

        echo json_encode($grouped_rows);

    }











    private function get_country_results($where) {
        $all_rows = [];

        $conn = new mysqli($this->db_details['servername'], $this->db_details['username'], $this->db_details['password'], $this->db_details['db_name']);
        
        $sql = "
        SELECT * FROM country $where;
        ";

        $result = $conn->query($sql);

        if ($result === FALSE) {die(GlobalVariables::$bad_url_mssg);}

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($all_rows, $row);
            }
        }
        return $all_rows;
    }










    private function get_cases_adjusted_15day_per_million_results($where) {
        $all_rows = [];
        $conn = new mysqli($this->db_details['servername'], $this->db_details['username'], $this->db_details['password'], $this->db_details['db_name']);
        
        $sql = "
        SELECT CountryID, DatapointDate, CasesAdj15Day1M FROM DailyData $where;
        ";

        $result = $conn->query($sql);

        if ($result === FALSE) {die(GlobalVariables::$bad_url_mssg);}

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($all_rows, $row);
            }
        }
        return $all_rows;
    }

    private function get_deaths_15day_per_million_results($where) {
        $all_rows = [];
        $conn = new mysqli($this->db_details['servername'], $this->db_details['username'], $this->db_details['password'], $this->db_details['db_name']);
        
        $sql = "
        SELECT CountryID, DatapointDate, Deaths15Day1M FROM DailyData $where;
        ";

        $result = $conn->query($sql);

        if ($result === FALSE) {die(GlobalVariables::$bad_url_mssg);}

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($all_rows, $row);
            }
        }
        return $all_rows;
    }

    private function get_mortalityRateByCases_results($where) {
        $all_rows = [];
        $conn = new mysqli($this->db_details['servername'], $this->db_details['username'], $this->db_details['password'], $this->db_details['db_name']);
        
        $sql = "
        SELECT CountryID, DatapointDate, MortalityVsCases FROM DailyData $where;
        ";

        $result = $conn->query($sql);

        if ($result === FALSE) {die(GlobalVariables::$bad_url_mssg);}

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($all_rows, $row);
            }
        }
        return $all_rows;
    }

}


$get_array = [];


foreach($_GET as $key=>$value) {
    $get_array[$key] = $value;
}

new QueryRouter($get_array);