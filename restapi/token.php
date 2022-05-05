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
$response = json_decode($response);

if( isset($response->access_token) ){
    $msg = "token recieved.";

    $params = array(
        "cc_token"=>$response->access_token
    );
   
    $update_db = $wpdb->update($table_name, $params, array("id"=>1));
    if($update_db){ 
        $msg= "success";
    }else{
        $msg.="token update failed";
    }
}else{
    $msg = "Token not recieved";
}

$send = array(
    "msg" => $msg,
    "token" => $response->access_token
);

echo (json_encode($send));