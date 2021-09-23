<?php

class CountryListParser {

    function __construct(){
        $ts = date("H:i:s");
        echo "CountryListParser __construct() is Called: $ts<br><br>";
        
        $this->all_lines = file("https://www.worldometers.info/coronavirus/");
        $this->country_list_array = [];

        $this->additional_fields = [
            'TestingIvermectin',
            'DistributionIvermectin',
            'Hemisphere',
            'Continent',
            'Region',
            'GDP',
            'HealthcareEfficiencyRank',
        ];

        $tick = 0;
        $country = '';

        $remove_these_01 = [
            '<td style="font-weight: bold; font-size:15px; text-align:left;"><a class="mt_a" href="country/',
            '</a></td>',
        ];
        
        $remove_these_02 = [
            '<td style="font-weight: bold; text-align:right;">',
            '<td style="font-weight: bold; text-align:right">',
            ' </td>',
            '</td>',
            '</a>',
        ];

        foreach($this->all_lines as $index => $line) {
            
            if (strpos($line, $remove_these_01[0]) !== false) {
                $inner_array = explode('/">', str_replace($remove_these_01, '', $line));
                
                if (!in_array($inner_array[1],array_keys($this->country_list_array))) {
                    $tick = $index;
                    $country = trim($inner_array[1]);
                    $this->instantiate_country_array($inner_array);
                }
                continue;
            }

            if (($index === ($tick + 1)) && ($this->string_check($line, $remove_these_02) === true)) {
                $total_cases = $this->get_integer(str_replace($remove_these_02, '', $line));
                $this->add_field_to_country_array($country, 'TotalCases', $total_cases);
                continue;
            }

            if (($index === ($tick + 3)) && ($this->string_check($line, $remove_these_02) === true)) {
                $total_deaths = $this->get_integer(str_replace($remove_these_02, '', $line));
                $this->add_field_to_country_array($country, 'TotalDeaths', $total_deaths);
                continue;
            }

            if (($index === ($tick + 14)) && ($this->string_check($line, $remove_these_02) === true)) {
                $population = $this->get_integer(trim(str_replace($remove_these_02, '', $line)));
                $this->add_field_to_country_array($country, 'Population', $population);

                $this->add_additional_fields_to_country_array($country);
            }
        }
    }

    private function string_check($check_this, $for_these) {
        $temp_01 = strpos($check_this, $for_these[0]);
        $temp_02 = strpos($check_this, $for_these[1]);
        return (($temp_01 !== false) || ($temp_02 !== false));
    }
    
    private function get_integer($string) {
        $temp = explode(">",$string);
        $temp = sizeof($temp) === 2 ? $temp[1] : $temp[0];
        return intval(str_replace(",","",$temp));
    }
    
    private function instantiate_country_array($inner_array) {
        $this->country_list_array[trim($inner_array[1])] = ['Slug'=>trim($inner_array[0])];
        $this->country_list_array[trim($inner_array[1])] += ['HtmlId'=>$this->remove_dashes(trim($inner_array[0]))];
    }

    private function add_field_to_country_array($country, $key, $value) {
        if (!isset($this->country_list_array[$country][$key])) {
            $this->country_list_array[$country][$key] = $value;
        } else {
            if ($value > $this->country_list_array[$country][$key]) {
                $this->country_list_array[$country][$key] = $value;
            } else {
                null;
            }
        }
    }

    private function add_additional_fields_to_country_array($country) {
        foreach ( $this->additional_fields as $key) {
            $this->country_list_array[$country] += [$key => null];
        }
    }
    
    private function remove_dashes($string) {
        return str_replace('-', '_', $string);
    }
    public function get_country_array(): array {
        return $this->country_list_array;
    }

}