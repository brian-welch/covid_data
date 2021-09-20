<?php


function horizontal_axis_labels($all_labels_array) {
    $label_string = '';

    foreach ($all_labels_array as $index => $date) {
        // switch ($index % 30) {
        //     case 0:
                $index === 0 ? $label_string .= ('"' . $date . '"') : $label_string .= (', "' . $date . '"');
        //         break;
        //     default:
        //     $label_string .= ', ""';
        // }
    }
    return ($label_string);
}

function vertical_axis_data($population, $data_array) {

    $data_string = '';

    $per_capita_data = function($integer) use ($population) {

        $per_capita = intval(round((($integer * 1.326) * 1000000) / $population, 0));
        return $per_capita;
    };

    $per_capita_data_array = array_map($per_capita_data, $data_array);

    foreach ($per_capita_data_array as $index => $case_count) {
        $index === 0 ? $data_string .= $case_count : $data_string .= (", " . $case_count);
    }

    return ($data_string);
}
