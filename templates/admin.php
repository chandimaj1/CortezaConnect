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


if(isset($_POST[‘submit’])){

}

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
    <form action="" method="post" name="pluggin_settings_form">
        <div id="settings_row">
        
            <?php
            $table_name = $wpdb->prefix."CortezaConnect_settings";
            $sql = "SELECT DISTINCT * FROM $table_name WHERE id=1";
            $result = $wpdb->get_results( $sql );    
            $msg = "fail";

            if($result){
                $tabs = '';
                foreach ($result[0] as $key=>$row){

                    if ($key!='id' && $key!='cc_token'){ //Hiding id and token
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
            <div class="col-md-9">
                <span id="settings_message"></span>
            </div>
            <div class="col-md-3">
                <button class="btn btn-success" id="update_settings">Update</button>
            </div>
        </div> 
    </form>
</div>


<div class="container-fluid stripe" style="margin-top:50px">
    <h4>Shortcode Selection</h4>
    <hr>
    <form action="" method="post" name="pluggin_settings_form">
        <div id="settings_row">

        <div class="mb-3">
            <label for="cc_shortcode_text" class="form-label">Shortcode Label</label>
            <input type="text" class="form-control" id="cc_shortcode_text" placeholder="">
        </div>
            

            <select id="cc_select_namespace" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option selected value="false" >Select Namespace</option>
            </select>
            <select id="cc_select_module" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option selected value="false" >Select Module</option>
            </select>
            <select id="cc_select_type" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option value="Show Information">Show information</option>
                <option value="Collect Information">Collect information</option>
            </select>
        </div>

        <div class="row" id="settings_buttons_row">
            <div class="col-md-9">
                <span id="settings_message"></span>
            </div>
            <div class="col-md-3">
                <button class="btn btn-success" id="refresh_selection">Refresh</button>
                <button class="btn btn-success" id="save_selection">Save</button>
            </div>
        </div> 
    </form>
</div>





