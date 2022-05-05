<?php
if (! defined( 'ABSPATH') ){
    die;
}

//setting script variables
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
$site_host = "https://";   
else  
$site_host = "http://";   
// Append the host(domain name, ip) to the URL.   
$site_host.= $_SERVER['HTTP_HOST']; 
$plugin_url = $site_host."/wp-content/plugins/cortezaconnect/";

global $wpdb;
    $table_name = $wpdb->prefix."cortezaconnect_settings";
    $sql = "SELECT DISTINCT * FROM $table_name WHERE id=1";
    $result = $wpdb->get_results( $sql );
    
    $msg = "fail";
    if($result){ 
        $msg= "success";
    }

//Get aircraft results

?>
<script type="text/javascript">
        var plugin_url = "<?= $plugin_url ?>";
    </script>
<div class="container-fluid" >

<!--
    Plugin Settings
-->

<div class="container-fluid stripe" style="margin-top:50px">
    <h4>CortezaConnect Settings</h4>
    <hr>
    <div id="settings_row">
    <?php
$table_name = $wpdb->prefix."CortezaConnect_settings";
$sql = "SELECT DISTINCT * FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );
    
$msg = "fail";

if($result){
    $tabs = '';
    foreach ($result[0] as $key=>$row){

        if ($key != 'id'){
?>
        <div class="row settings_set_row">
            <div class="col-sm-4"><?= $key ?></div>
            <div class="col-sm-8">
                <input type="text" class="settings_row_set form-control" id="settings_<?= $key ?>"  value="<?= $row ?>" />
            </div>
        </div>

<?php
        }
    }
}else{
    echo ('Error getting settings from database.');
}
?>
    </div>
    <div class="row" id="settings_buttons_row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button class="btn btn-success" id="update_settings">Update</button>
            </div>
        </div> 
</div>





