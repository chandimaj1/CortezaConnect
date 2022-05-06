<?php;
global $wpdb;
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//Params
$params = $params->get_json_params();
$data = $params["data"];

$token = $data["cc_token"];
$endpoint = $data["endpoint"];
$method = $data["method"];
$data = $data["data"];


//Getting settings from database
$table_name = $wpdb->prefix."CortezaConnect_settings";
$sql = "SELECT DISTINCT cc_instance_url, cc_token FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );
$result = $result[0];

$instance = $result->cc_instance_url;
$basic_auth = 'Authorization: Bearer '.$result->cc_instance_url;

echo ($basic_auth);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $instance.$endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=profile%20api',
    CURLOPT_HTTPHEADER => array(
      $basic_auth,
      'Content-Type: application/x-www-form-urlencoded'
    ),
  ));
  
  $response = curl_exec($curl);
  curl_close($curl);

$msg = "Error getting token";
$status = true;
$response = json_decode($response);

if( isset($response->set) ){
    $msg = "Information recieved.";
}else{
    $msg = "Information not recieved.";
    $status = false;
}

$send = array(
    "msg" => $msg,
    "response" => $response,
    "status" => $status
);

echo (json_encode($send)); 