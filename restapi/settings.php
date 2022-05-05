<?php
global $wpdb;
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}


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

//GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT DISTINCT * FROM $table_name WHERE id=1";
    $result = $wpdb->get_results( $sql );
    $response = $result;
}

//POST
else if (
    $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($data) && !empty($data)
    && isset($data["cc_user_id"]) && !is_null($data["cc_user_id"]) 
    && isset($data["cc_secret"]) && !is_null($data["cc_secret"]) ) {
    $data["id"]=1; //Set row id as 1
    $update_db = $wpdb->replace($table_name, $data);
    if($update_db){ 
        $msg= "success";
    }else{
        $msg="failed";
    }
}



//Save Settings
function save_settings($data){
    
}


//Send results
$send = array(
    "msg"=>"$msg",
    "response"=>"$response"
);

$send = json_encode($send);
echo ($send);