<?php

class CountryListParser {

    function __construct(){
        echo "CountryListParser is Called";

        $this->file_array = file("https://www.worldometers.info/coronavirus/");
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

        foreach($this->file_array as $index => $line) {
            
            if (strpos($line, $remove_these_01[0]) !== false) {
                $inner_array = explode('/">', str_replace($remove_these_01, '', $line));
                
                if (!in_array($inner_array[1],array_keys($this->country_list_array))) {
                    $tick = $index;
                    $country = trim($inner_array[1]);
                    $this->populate_country_array($inner_array);
                }
            }

            if (($index === ($tick + 1)) && ($this->string_check($line, $remove_these_02) === true)) {
                $total_cases = $this->get_integer(str_replace($remove_these_02, '', $line));
                $this->add_to_country_array(['TotalCases'=>$total_cases], $country);
            }

            if (($index === ($tick + 3)) && ($this->string_check($line, $remove_these_02) === true)) {
                $total_deaths = $this->get_integer(str_replace($remove_these_02, '', $line));
                $this->add_to_country_array(['TotalDeaths'=>$total_deaths], $country);
            }

            if (($index === ($tick + 14)) && ($this->string_check($line, $remove_these_02) === true)) {
                $population = $this->get_integer(trim(str_replace($remove_these_02, '', $line)));
                $this->add_to_country_array(['Population'=>$population], $country);

                $this->add_these_to_country_array($country);
            }

        }
        usleep(intval(substr(round(rand()*.01),0,7)));
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
    
    private function populate_country_array($inner_array) {
        $this->country_list_array[trim($inner_array[1])] = ['Slug'=>$inner_array[0]];
        $this->country_list_array[trim($inner_array[1])] += ['HtmlId'=>$this->remove_dashes($inner_array[0])];
    }

    private function add_to_country_array($key_value_pair, $country) {
        $this->country_list_array[$country] += $key_value_pair;
    }

    private function add_these_to_country_array($country) {
        foreach ( $this->additional_fields as $key) {
            $this->country_list_array[$country] += [$key => null];
        }
    }

    private function remove_country($country) {
        unset($this->country_list_array[$country]);
    }
    
    private function remove_dashes($string) {
        return str_replace('-', '_', $string);
    }
    public function get_country_array(): array {
        return $this->country_list_array;
    }

}