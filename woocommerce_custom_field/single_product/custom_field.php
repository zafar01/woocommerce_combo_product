<?php

/****************************** *************** 
Display Custome Fields on Product Details Page
****************************** ***************/  
function woocommerce_custom_fields_display()
{
	
	
  global $post;
  
    $product = wc_get_product($post->ID);
    $custom  = $product->get_meta( 'custom_text_field_title' );
    $product_limit  = $product->get_meta('custom_product_limit');
    $custom_subscribe = $product->get_meta('custom_subscribe');
	$custom_number_of_subscription = $product->get_meta('custom_number_of_subscription');
	if($custom_subscribe == 'Subscription'){
	$_product_type = $custom_number_of_subscription.' '.$custom_subscribe;
	}else{$_product_type = $custom_subscribe;}
	
	
	/** Display for Gift, Subscription and Combo **/
if($custom_subscribe == 'Subscription' || $custom_subscribe == 'Combo' || $custom_subscribe == 'Gift'){ 
	 $allproduct = json_decode($custom);
 $allroow = '<input type="hidden" id="producttype" name="product_type" value="'.$_product_type.'">';
 if($custom_number_of_subscription > 0){
 $allroow .= '<input type="hidden" id="subscribelimit" value="'.$custom_number_of_subscription.'">';
 }
   if (count($allproduct) > 0 ) 
  { 
	
$allroow .= '<table id="qutyc"><tr>
	<td>Img</td>
	<td>Name</td>
	<td>Qty</td>
	</tr>';	
if(!empty($allproduct)){	
	 foreach($allproduct as $key => $al){
		$product = wc_get_product( $al ); 
		$ids = $product->get_id();
    //	$img = $product->get_image();
    $image = wp_get_attachment_image_src( get_post_thumbnail_id($ids ), 'single-post-thumbnail' );
    
	$svalu = null;
	if($key == 0){$svalu =  $product_limit;}
	$name = $product->get_name();
	$allroow .= '<tr>
	 
	<td> <img src="'. $image[0] .'"  style="width:50px" ></td>
	<td>'.$product->get_name() .'</td>
	<td><input type="number" id="cqty'.$key.'"  onkeyup="count(this.value)"    name="cqty[ '.$name.' ]" style="width:86px" value="'. $svalu .'" ></td>
	</tr> ';	 
	 }
} 
	  $allroow .= '</table><input type="hidden" id="validte" value="'.$product_limit.'">
	  <span id="verror"></span>
	  ';
      printf(
            '<div><label>%s</label> 
			'. $allroow .'
			</div>',
            esc_html($custom_fields_woocommerce_title)
      );
       
  }
  
}
}
 
add_action('woocommerce_before_add_to_cart_button', 'woocommerce_custom_fields_display');

function my_theme_scripts() {
    wp_enqueue_script( 'fontend_custom_field', plugin_dir_url( __FILE__ ) . 'js/custom_field_qty.js', array( 'jquery' ), '1.0.0', true );
	
	 
}
add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );