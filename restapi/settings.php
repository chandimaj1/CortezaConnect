<?php
//Import wpdb from wordpress
/*
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}
*/

//Incoming
$method = $_POST['method'];
$data = $_POST['data'];

var_dump($_POST);

//Responses
$msg = 'Error! Unknown';
$response = array();

$table_name = $wpdb->prefix."CortezaConnect_settings";

//Method Selection
switch ($method){
    case 'verify':
        verify_settings($data);
        break;
}

//Save Settings
function save_settings($data){
    $data["id"]=1; //Set row id as 1
    $update_db = $wpdb->replace($table_name, $data);
        if($update_db){ 
            $msg= "success";
        }else{
            $msg="failed";
        }
}


//Send results
$send = array(
    "msg"=>"$msg",
    "response"=>"$response"
);

$send = json_encode($send);
echo ($send);