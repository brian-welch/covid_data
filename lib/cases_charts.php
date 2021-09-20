<?php

include 'country_covid_data.php';
include 'daily_data_calculations.php';


function render_all_country_cases($all_countries_data) {

    $prettyPopulation = function($population) {
        $int_array = str_split($population);
        $number_string = '';
        $counter = count($int_array);
        
        for ($i = 1; $i <= $counter; $i++ ) {
            $temp = array_pop($int_array);
            
            (($i % 3 === 0) && (count($int_array))) > 0 ? $number_string = ',' . $temp . $number_string : $number_string = ($temp . $number_string);
        };
        return $number_string;
    };
    $chart_id = function($slug) {
        return str_replace("-", "", $slug);

    };
    
    $counter = 0;
    foreach ($all_countries_data as $country_name => $country_data) {
        ++$counter;


        $labels = horizontal_axis_labels($country_data['dates']);
        $data = vertical_axis_data($country_data['population'], $country_data['cases']);

        echo <<<CHART_BLOCK

            <div class="col-xs-12 col-md-6 chart-container">
                <div class="chart-title">{$country_name} - Pop: {$prettyPopulation($country_data['population'])}</div>
                <canvas id="myChart_{$chart_id($country_data['slug'])}"  style=""></canvas>
            </div>

        <script>
            var chart_canvas = document.getElementById('myChart_{$chart_id($country_data['slug'])}').getContext('2d');
            var myChart_{$chart_id($country_data['slug'])} = new Chart(chart_canvas, {
                type: 'line',
                data: {
                    labels: [$labels],
                    datasets: [{
                        label: ' ',
                        data: [$data],

                        borderColor: [
                            'rgba(50, 25, 150, 1)',

                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    animation: {
                        duration: 2000,
                        delay: 1000,
                    },
                    scales: {
                        yAxis: {
                            beginAtZero: true,
                            max: 2500,
                            ticks: {
                                font: {
                                    size: 9,
                                },
                                fontColor: 'rgba(50, 25, 250, 1)'
                            },
                        },
                        xAxis: {
                            ticks: {
                                font: {
                                    
                                    size: 0.1,
                                },
                            },
                        },
                    },
                    elements: {
                        point: {
                            pointStyle: '',
                            radius: 0,
                            pointBorderWidth: 2,
                            pointBackgroundColor: ['rgba(50, 25, 150, 1)',],
                            pointBorderColor: ['rgba(50, 25, 150, 1)',],
                        },
                        line: {
                            borderColor: [
                                'rgba(0, 0, 0, 1)',
                            ],
                            borderJoinStyle: 'round',
                            tension: 0,
                        },
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                }
            });
        </script>
CHART_BLOCK;

    }
}