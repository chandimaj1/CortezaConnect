<?php

require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

$target_file = '../airports.csv';


function save_csv_in_table($target_file){
    global $wpdb;

    $msg = 'Error! Unknown';

    $totalInserted = 0;
    $totalInCSV = 0;

    $tablename = $wpdb->prefix."flightbook_airports";

    //Empty current table
    $wpdb->query("TRUNCATE TABLE $tablename");

    // Import CSV

    // Open file in read mode
    $csvFile = fopen($target_file, 'r');

    fgetcsv($csvFile); // Skipping header row

    // Read file
    while(($csvData = fgetcsv($csvFile)) !== FALSE){
        
        $csvData = array_map("utf8_encode", $csvData);

        // Row column length
        $dataLen = count($csvData);

        echo ($dataLen);
        // Skip row if length != 9
        if( $dataLen != 9 ) continue;

        // Assign value to variables
        $airport_name = trim($csvData[0]);
        $airport_city = trim($csvData[1]);
        $country_name = trim($csvData[2]);
        $airport_iata = trim($csvData[3]);
        $airport_icao = trim($csvData[4]);
        $gmt = trim($csvData[5]);
        $country_code = trim($csvData[6]);
        $latitude = trim($csvData[7]);
        $longitude = trim($csvData[8]);

        // Check record already exists or not
        $cntSQL = "SELECT count(*) as count FROM {$tablename} WHERE ( 
            (airport_iata<>'' AND airport_iata='$airport_iata') OR 
            (airport_icao<>'' AND airport_icao='$airport_icao')
            )";
        $record = $wpdb->get_results($cntSQL, OBJECT);

        //If record no exists
        if($record[0]->count==0){
 
            // Insert Record 
            
            $wpdb->insert($tablename, array(
            'airport_name' =>$airport_name,
            'airport_city' =>$airport_city,
            'country_name' =>$country_name,
            'airport_iata' =>$airport_iata,
            'airport_icao' => $airport_icao,
            'gmt' => (float)$gmt,
            'country_code' => $country_code,
            'latitude' => (float)$latitude,
            'longitude' => (float)$longitude,
            'status' => 1
            ));
             
            //If insert success
            if($wpdb->insert_id > 0){
                $totalInserted++;
            }
        }
        //Record CSV line read
        $totalInCSV++;
        
    }
    
    $return = array("totalInserted"=>$totalInserted, "totalInCSV"=>$totalInCSV);
    return ($return);
}


$return = save_csv_in_table($target_file);
$return = json_encode($return);
echo ($return);

?>