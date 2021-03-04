<?php
/*
Plugin name: Woocommerce Custome fields 
*/


include_once dirname( __FILE__ ) . '/admin/admin.php';

/**** Display custom file on product details plage */
include_once dirname( __FILE__ ) . '/single_product/gift_product.php';
include_once dirname( __FILE__ ) . '/single_product/subscribe_product.php';
include_once dirname( __FILE__ ) . '/single_product/custom_field.php';
include_once dirname( __FILE__ ) . '/single_product/add_cart_item_data.php';
// Add subscribe order in order table
include_once dirname( __FILE__ ) . '/single_product/insert_subscribe_order.php';


/*********** My Account Dashboard Add new Tab ****/
include_once dirname( __FILE__ ) . '/my_account_tab/my_account_tab.php';
include_once dirname( __FILE__ ) . '/my_account_tab/add_subscribe_button.php';



/*********** Admin menu for subscribe Tab ****/
include_once dirname( __FILE__ ) . '/subscribe_city/admin_menu_city.php';
include_once dirname( __FILE__ ) . '/subscribe_city/data_table_script.php';
include_once dirname( __FILE__ ) . '/subscribe_city/subscribe_city.php';
include_once dirname( __FILE__ ) . '/subscribe_city/subscribe_order.php';

/***** this css and js using only for admin this hooks is attache with admin menu **/
 function vertical_news_scroller_plugin_admin_init(){    
        $url = plugin_dir_url(__FILE__);
        wp_enqueue_script('jquery');
        wp_enqueue_style( 'admin-css', plugins_url('/css/admin-css.css', __FILE__) );   
       
    	
		
		 
    }
	
	
	
	
	
/******* this css and js for frontend **/
function add_theme_scripts() {
  wp_enqueue_style( 'style', get_stylesheet_uri() );
 
  wp_enqueue_style( 'subscribe_city', plugins_url('/css/customcssstyle.css', __FILE__), array(), '1.1', 'all');
 
  wp_enqueue_script( 'ajax_city', plugins_url('/js/ajax_city.js', __FILE__) , array ( 'jquery' ), 1.1, true);
  
  wp_enqueue_script( 'gift_datepicker', plugins_url('/single_product/js/gift_datepicker.js', __FILE__) , array ( 'jquery' ), 1.1, true);
  
  
  /**** Date picker script ***/
  wp_enqueue_style( 'datepiccss', plugins_url('/css/datepick.css', __FILE__), array(), '1.1', 'all');
  
  
   wp_enqueue_script( 'datepickjs','https://code.jquery.com/ui/1.12.1/jquery-ui.min.js' , array ( 'jquery' ), 1.1, true);   
   wp_enqueue_script( 'datepick','https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js' , array ( 'jquery' ), 1.1, true); 
  wp_enqueue_script( 'datepicksl', plugins_url('/js/datepicker.js', __FILE__) , array ( 'jquery' ), 1.1, true);
  
  /******* End Date Picker ***/
  
  
  
}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );
	
	
	
	/* admin Include CSS and Script */
add_action('admin_enqueue_scripts','plugin_css_jsscripts');
function plugin_css_jsscripts() {
   
wp_enqueue_script( 'ajax_order_stauts', plugins_url('/js/ajax_order_status.js', __FILE__) , array ( 'jquery' ), 1.1, true);
   // JavaScript
 //  wp_enqueue_script( 'script-js', plugins_url( '/script.js', __FILE__ ),array('jquery'));

   // Pass ajax_url to script.js
   wp_localize_script( 'ajax_order_stauts', 'aplugin_ajax_object',
   array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}



/********* Create Database Table for subscrbi Cities ***/
register_activation_hook(__FILE__,'install_subscribecity'); 
function install_subscribecity(){

        global $wpdb;
        $table_name = $wpdb->prefix . "subscribe_cities";
       
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $table_name . " (
        id int(10) unsigned NOT NULL auto_increment,
        name varchar(10) NOT NULL,
		subsdays text NOT NULL,
        createdon datetime NOT NULL,
        status int(10) unsigned NOT NULL DEFAULT '1',
        PRIMARY KEY  (id)
        ) $charset_collate;";



        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
       
        
         
         
         // vns_vertical_news_scroller_add_access_capabilities();


    } 
	
	
	
/*****************************************************************************  
Add CSS and Jquery for admin select products multiple
*****************************************************************************/


function woo_product_add() {
    wp_enqueue_script( 'custom_admin_script', plugin_dir_url( __FILE__ ) . '/js/addproducts.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'woo_product_add' );

function admin_style() {
  wp_enqueue_style('admin-styles_product', plugin_dir_url( __FILE__ ).'/css/product_popup.css');
}
add_action('admin_enqueue_scripts', 'admin_style');



	
	
/********* Create Database Table for subscrbi Cities ***/
register_activation_hook(__FILE__,'my_plugin_create_db'); 	
	function my_plugin_create_db() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_names = $wpdb->prefix . 'subscribe_order';

	$sql = "CREATE TABLE $table_names (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time timestamp NOT NULL,
		invoice int(10) NOT NULL,
		day varchar(25) NOT NULL,
		city varchar(25) NOT NULL,
		products text NOT NULL,
		status int(5) NOT NULL,
		product_id int(10) NOT NULL,
		products_details text NOT NULL,
		subscribe_limit int(10) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
	 
	
	
	
	
	
	
	 