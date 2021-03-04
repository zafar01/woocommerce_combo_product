<?php
function woocommerce_subscribe_product_field()
{
	global $post;
	global $wpdb;
	$product = wc_get_product($post->ID);
	$custom_subscribe = $product->get_meta('custom_subscribe');
	if($custom_subscribe == 'Subscription'){
     $city_option = '<p>   <select class="customselect" name="Delivery_City" id="dcity" required ><option value="">Select Delivery City</option>';
    $product = wc_get_product($post->ID);
    $custom_subscribe = $product->get_meta('custom_subscribe');
	if($custom_subscribe == 'Subscription'){
    $query="SELECT name FROM ".$wpdb->prefix."subscribe_cities group by name";	
    $result = $wpdb->get_results($query);
	
	
	foreach($result as $rs)
	{
		$city_option .= '<option value="'.$rs->name.'">'.$rs->name.'</option>';
		}
	
	}
	$city_option .= '</select></p>
	<p><select id="days" name="Delivery_Day" class="customselect" required><option value="1">First Select City</option></select></p>
	';
	
	
	$city_option .='<div id="mdp-demo" name="dat"></div> 
	<input type="hidden" name="delivery_date" id="deldate">'; 
	
	
	printf(
            '<div><label>%s</label> 
			'. $city_option .'
			</div>',
            esc_html($custom_fields_woocommerce_title)
      );
	}
}


add_action('woocommerce_before_add_to_cart_button', 'woocommerce_subscribe_product_field');



/******/
 

//add_action( 'wp_ajax_my_action', 'my_action' );
add_action( 'wp_ajax_my_action', 'my_action_s' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action_s' ); //

function my_action_s() {
	global $wpdb; // this is how you get access to the database
    $opt  ='<option value="1"> Select Day</option>';
	$city =  ( $_POST['city'] );
  $query="SELECT subsdays FROM ".$wpdb->prefix."subscribe_cities where name ='$city'";	
    $resul = $wpdb->get_results($query);
	
	$dayar = array();
	if(!empty($resul)){
 foreach($resul as $rl){
	 if($rl->subsdays == 'Sunday'){$dayar[] = 0;}elseif($rl->subsdays == 'Monday'){$dayar[] = 1;}elseif($rl->subsdays == 'Tuesday'){$dayar[] = 2;}elseif($rl->subsdays == 'Wednesday'){$dayar[] = 3;}elseif($rl->subsdays == 'Thursday'){$dayar[] = 4;}elseif($rl->subsdays == 'Friday'){$dayar[] = 5;}elseif($rl->subsdays == 'Saturday'){$dayar[] = 6;}
	 
	 $opt  .='<option>'.$rl->subsdays.'</option>';
 }
 
 
 echo json_encode($dayar);
// echo $opt;
	die(); // this is required to terminate immediately and return a proper response
}
}