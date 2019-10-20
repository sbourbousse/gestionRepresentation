<?php
function skip_accents( $str, $charset='utf-8' ) {

    $str = htmlentities( $str, ENT_NOQUOTES, $charset );

    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );

    return $str;
}

function convertDate ($date)
{
    if ($date[8]=='0')$date[8]=' ';
    if ($date[5].$date[6]=='01') return $date[8].$date[9].' Janvier '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='02')return $date[8].$date[9].' Fevrier '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='03')return $date[8].$date[9].' Mars '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='04')return $date[8].$date[9].' Avril '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='05')return $date[8].$date[9].' Mai '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='06')return $date[8].$date[9].' Juin '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='07')return $date[8].$date[9].' Juillet '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='08')return $date[8].$date[9].' Aout '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='09')return $date[8].$date[9].' Septembre '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='10')return $date[8].$date[9].' Octobre '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='11')return $date[8].$date[9].' Novembre '.$date[0].$date[1].$date[2].$date[3];
    else if ($date[5].$date[6]=='12')return $date[8].$date[9].' Decembre '.$date[0].$date[1].$date[2].$date[3];

}

function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
    foreach ($array as $line) { 
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter); 
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
}
?>