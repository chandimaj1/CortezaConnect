<?php;
global $wpdb;
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//Params
$params = $params->get_json_params();
$params = $params["data"];

$endpoint = $params["endpoint"];
$method = $params["method"];
$data = $dparams["data"];

var_dump($params);


//Getting settings from database
$table_name = $wpdb->prefix."CortezaConnect_settings";
$sql = "SELECT DISTINCT cc_instance_url, cc_token FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );
$result = $result[0];

$url = $result->cc_instance_url.$endpoint;
$basic_auth = 'Authorization: Bearer '.$result->cc_token;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => $method,
  CURLOPT_HTTPHEADER => array(
    $basic_auth
  ),
));

$response = curl_exec($curl);
  curl_close($curl);

$msg = "Error getting token";
$status = true;
$response = json_decode($response);

if( isset($response->response) && !empty($response->response) ){
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