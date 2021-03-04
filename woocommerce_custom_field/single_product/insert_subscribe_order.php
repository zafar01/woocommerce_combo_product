<?php

 add_action( 'woocommerce_thankyou', 'elit_woocommerce_new_order');
  
  // add_action( 'woocommerce_order_status_completed', 'elit_woocommerce_new_order');
function elit_woocommerce_new_order( $order_id ){
    $order = wc_get_order( $order_id );
    if ( ! $order->has_status( 'failed' ) ) {
	
 /********************************************************************************************************************************************************************************/
 
 
 $order = wc_get_order( $order_id);
 global $wpdb;
 $tablename = $wpdb->prefix.'subscribe_order';

// echo '<pre>';
// print_r($order->get_items());
 
 $x=0;
foreach ( $order->get_items() as $item_id => $item ) {

 //echo 'Qty: '.$item->get_quantity(); 
 //echo '<br>';
    // Here you get your data
    $custom_field = wc_get_order_item_meta( $item_id, 'Product Type', true );
    $subscribe_limit = explode(' ',$custom_field);
	
	 
	 
								
   if($subscribe_limit[1] == 'Subscription'){
	   
	   $total_deleve = ($subscribe_limit[0] * $item->get_quantity());
	 
	 
	 for($i=1;$i <= $total_deleve;$i++){
		 $sublimi =0;
		 if($i % $subscribe_limit[0] == 1){$sublimi = $subscribe_limit[0];$x=0;}
		 
		 $deliveryda = explode(',',wc_get_order_item_meta( $item_id, 'Delivery date', true ));
		 
		$wpdb->insert( $tablename, array(
		 'invoice' => $order_id,
		 'day' => $deliveryda[$x],
		 'products' => wc_get_order_item_meta( $item_id, 'Products in combo', true ),
		 'status' => 1,
		 'product_id' => $item->get_product_id(),
		 'products_details' => wc_get_order_item_meta( $item_id, 'Products_in_json', true ),
		 'subscribe_limit' => $sublimi,
		 'city' => wc_get_order_item_meta( $item_id, 'Delivery City', true )
		 ), 
                                array( 
                                        '%s', 
                                        '%s', 
                                        '%s', 
                                        '%s', 
										'%s', 
										'%s', 
										'%s',
                                ) );
								
								$x++;
								 
	 }

	  
   }
     

    
}
 
 
 
 
 
 /*********************************************************************************************************************************************************************/
 
	
	}
	
}