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
        <div class="row" id="settings_buttons_row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button class="btn btn-warning" id="reset_settings">Reset</button>
                <button class="btn btn-success" id="update_settings">Update</button>
            </div>
        </div>
    <?php
$table_name = $wpdb->prefix."cortezaconnect_settings";
$sql = "SELECT DISTINCT * FROM $table_name id=1";
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
</div>



<!--
    Theme Settings
-->

<div class="container-fluid stripe" style="margin-top:50px">
    <h4>Theme Settings</h4>
    <hr>
    <div id="themes_row">
        <div class="row" id="settings_buttons_row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button class="btn btn-warning" id="reset_theme_settings">Reset</button>
                <button class="btn btn-success" id="update_theme_settings">Update</button>
            </div>
        </div>
    <?php
$table_name = $wpdb->prefix."cortezaconnect_settings";
$sql = "SELECT DISTINCT * FROM $table_name";
$result = $wpdb->get_results( $sql );
    
$msg = "fail";

if($result){
    $tabs = '';
    foreach ($result[0] as $key=>$row){

        if ($key != 'id'){
?>
        <div class="row themes_set_row">
            <div class="col-sm-4"><?= $key ?></div>
            <div class="col-sm-8">
                <input type="text" class="themes_row_set form-control" id="themes_<?= $key ?>"  value="<?= $row ?>" />
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
</div>


<div class="modal fade" id="ac_img_viewer" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" style="width:100%">
      </div>
      <div class="modal-footer">
        <a href="" target="_blank"><button type="button" class="btn btn-primary">Full View</button></a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





