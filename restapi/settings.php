<?php
global $wpdb;
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}


//Incoming
$params = $params->get_json_params();
$data = $params["data"];
//Responses
$msg = 'Error! Unknown';
$status = true;
$response = array();

$table_name = $wpdb->prefix."CortezaConnect_settings";

//GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT DISTINCT * FROM $table_name WHERE id=1";
    $result = $wpdb->get_results( $sql );
    if ($result){
        $response = $result;
        $msg = "success";
    }else{
        $msg = "failed";
        $status = false;
    }
}

//POST
else if (
    $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data["id"]=1; //Set row id as 1
    $update_db = $wpdb->replace($table_name, $data);
    if($update_db){ 
        $msg= "success";
    }else{
        $msg="failed";
        $status = false;
    }
}


//Send results
$send = array(
    "msg"=>"$msg",
    "response"=>$response,
    "status"=>$status
);

$send = json_encode($send);
echo ($send);