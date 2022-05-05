<?php
global $wpdb;
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}


//Getting settings from database
$table_name = $wpdb->prefix."CortezaConnect_settings";
$sql = "SELECT DISTINCT * FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );
$result = $result[0];

$basic_auth = "Authorization: Basic ".base64_encode($result->cc_user_id.":".$result->cc_secret);

$curl = curl_init();
$instance = $result->cc_instance_url;

curl_setopt_array($curl, array(
    CURLOPT_URL => $instance.'/auth/oauth2/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
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
$token = $response->access_token;

if( isset($response->access_token) ){
    $msg = "token recieved.";

    $update_db = $wpdb->update($table_name, array("cc_token" => $token), array("id"=>1));
    if($update_db){ 
        $msg.= "Token updated!.";
    }else{
        $msg.="token update failed.";
        $status = false;
    }
}else{
    $msg = "Token not recieved.";
    $status = false;
}

$send = array(
    "msg" => $msg,
    "token" => $token,
    "table" => $table_name,
    "status" => $status
);

echo (json_encode($send)); 