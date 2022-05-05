<?php
/**
 * @package corteza_connect
 */

 /* 
 Plugin Name: Corteza Connect
 Plugin URI:  http://planetcrust.com/
 Description: Corteza Integration plugin on Wordpress
 Version: 1.0
 Author: PlanetCrust
 Author URI: http:/planetcrust.com/
 License: GPLV2 or later
 Text Domain: corteza_connect
 */

 /*
 This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if (! defined( 'ABSPATH') ){
    die;
}

class CortezaConnect
{

    public $plugin_name;
    public $settings = array();
    public $sections = array();
    public $fields = array();

    //Method Access Modifiers
    // public - can be accessed from outside the class
    // protected - can only be accessed within the class ($this->protected_method())
    // protected - can only be accessed from constructor

    function __construct(){
        //add_action ('init', array($this, 'custom_post_type')); // tell wp to execute method on init
        $this->plugin_name = plugin_basename( __FILE__ );
    }

    function register(){
        add_shortcode( 'CortezaConnect', array($this, 'shortcode_frontend') );

        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue') );

        add_action ( 'admin_menu', array( $this, 'add_admin_pages' ));
        add_filter ("plugin_action_links_$this->plugin_name", array ($this, 'settings_link'));

       // add_filter( 'single_template', array($this, 'load_custom_post_specific_template'));
    }


    function add_admin_pages(){
        add_menu_page( 'CortezaConnect - Settings', 'CortezaConnect - Settings', 'manage_options', 'CortezaConnect_settings', array($this,'admin_index'), 'dashicons-list-view', 100);
        //add_menu_page( 'VeloxJets Pricing - Airports Table', 'VeloxJets Pricing - Airports', 'manage_options', 'CortezaConnect_airports_settings', array($this,'admin_airports'), 'dashicons-list-view', 101);
    }    

    function admin_index(){
        //require template
        require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
    }

    function admin_airports(){
        require_once plugin_dir_path( __FILE__ ) . 'templates/airports.php';
    }


    function settings_link($links){
        //add custom setting link
        $settings_link = '<a href="admin.php?page=CortezaConnect_settings">Plugin Settings</a>';
       // $airports_settings_link = '<a href="admin.php?page=CortezaConnect_airports_settings">Airports Settings</a>';
        array_push ( $links, $settings_link );
        // array_push ( $links, $airports_settings_link );
        return $links;
    }


    function activate(){
        // Plugin activated state
        // generate a Custom Post Style
        // $this->custom_post_type();
        $this->create_table();
        // Flush rewrite rules 
        flush_rewrite_rules();
    }
 
    function deactivate(){
        // Plugin deactivate state
        //Flush rewrite rules
        flush_rewrite_rules();
    }

    function uninstall(){
        //Plugin deleted
        //delete Custom Post Style
        //delete all plugin data from the DB

    }
 

    function create_table(){ 
        // create table if not exist
        global $wpdb;

        // aircrafts
        $table_name = $wpdb->prefix."CortezaConnect_settings";
        if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
            $sql = 'CREATE TABLE '.$table_name."(
            id INTEGER NOT NULL AUTO_INCREMENT,
            cc_instance_url VARCHAR(50),
            cc_user_id VARCHAR(50),
            cc_secret VARCHAR(100),
            cc_token VARCHAR(100),
            PRIMARY KEY  (id))";

            $sql_insert = "INSERT INTO $table_name (id, cc_instance_url, cc_user_id, cc_secret, cc_token) VALUES
            (1, 'https://pyd-sandbox.staging.crust.tech', '281603202489516048', 'k9ZdWmdq7a82iDZnG4hklvfVTeOxCr9uKMPGSAnsU8Pj4wM8JEp09hN1iIiehHA8', 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRJRCI6IjI4MTYwMzIwMjQ4OTUxNjA0OCIsImV4cCI6MTY1NDIzODU4NSwiaWF0IjoxNjUxNjQ2NTg1LCJpc3MiOiJjb3J0ZXphcHJvamVjdC5vcmciLCJqdGkiOiJZSkJIWkdFMU5XSVRNSkZMWlMwWlpKS1pMV0lZTlRVVE5ETTBOVEVaWk1NM01XUkwiLCJyb2xlcyI6WyIyNjA1NjgwMjM4MDk2NTQ3ODciXSwic2NvcGUiOiJwcm9maWxlIGFwaSIsInN1YiI6IjI2MDk4ODM5MDc5NzczNzk4NyJ9.PN3xpa31zWrxqZ4lo8bgpUptpL_Tt1fEoFuajJl7VoY7_6Ode3yOL-mfCcdTObH1DMBUB1SAlvqdI-NbZPoF_g');";

            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            dbDelta($sql_insert);
            add_option("CortezaConnect_settings_db", "1.0");
        }

    }
    
    //Enqueue on admin pages
    function enqueue_admin($hook_suffix){

        if (strpos($hook_suffix, 'CortezaConnect_settings') !== false) {
            //Bootstrap
            wp_enqueue_style( 'bootstrap4_styles', plugins_url('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',__FILE__));
            wp_enqueue_script( 'bootstrap4_scripts', plugins_url('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',__FILE__), array('jquery'));
           
            //Admin scripts and styles
            wp_enqueue_style( 'CortezaConnect_admin_styles', plugins_url('/assets/CortezaConnect_admin_style.css',__FILE__));
            wp_enqueue_script( 'CortezaConnect_admin_script', plugins_url('/assets/CortezaConnect_admin_scripts.js',__FILE__), array('jquery'));
        }
    }

    //Enqueue on all other pages
    function enqueue(){ 

    }

    //------ Shortcode
    function shortcode_frontend($atts){
        include 'templates/CortezaConnect_shortcode.php';
    }
    
}

if ( class_exists('CortezaConnect') ){
    $flight_book = new CortezaConnect();
    $flight_book -> register();
}

//activate
register_activation_hook (__FILE__, array($flight_book, 'activate'));

//deactivation
register_deactivation_hook (__FILE__, array($flight_book, 'deactivate'));