<?php
global $wpdb;
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}


//Getting settings from database
$sql = "SELECT DISTINCT * FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $result->cc_instance_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_USERPWD => $result->cc_cc_user_id . ":" . $result->$cc_secret,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=profile%20api',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;