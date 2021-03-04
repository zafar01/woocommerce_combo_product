<?php

add_action('woocommerce_before_add_to_cart_button', 'woocommerce_Gift_product_field');


function woocommerce_Gift_product_field(){
    global $post;
	global $wpdb;
	$product = wc_get_product($post->ID);
	$custom_subscribe = $product->get_meta('custom_subscribe');
	if($custom_subscribe == 'Gift'){
		 
		$city_option .='<div id="gift-demo" name="dat"></div> 
	<input type="hidden" name="gift_delivery_date" id="gdeldate">'; 
	
	
	printf(
            '<div><label>%s</label> 
			'. $city_option .'
			</div>',
            esc_html($custom_fields_woocommerce_title)
      );
		
	}

}