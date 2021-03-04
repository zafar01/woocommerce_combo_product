<?php


/*******************************************************
************/

// Your additional action button
add_filter( 'woocommerce_my_account_my_orders_actions', 'add_my_account_my_orders_custom_action', 10, 2 );
function add_my_account_my_orders_custom_action( $actions, $order ) {
    $action_slug = 'specific_name';
	$co = 0;
	
	$subscribe_button = null;
	 
	if(!empty($order->get_items())){
		//echo '<pre>';
		//print_r($order->get_items());exit;
	 foreach ( $order->get_items() as $item_id => $item ) {
		 if($co ==0){
			 $co++;
	$subscribe_button = wc_get_order_item_meta( $item_id, 'Delivery date', true ); 
 
		 }
    }	
	}
	
	if($subscribe_button){
		
    $actions[$action_slug] = array(
        'url'  => home_url('/my-account/premium-support/'.$order->id),
		'name' => 'Subscribe Order View'.$subscribe_button,
    );
	}
	
	
    return $actions;
	
}

// Jquery script
add_action( 'woocommerce_after_account_orders', 'action_after_account_orders_js');
function action_after_account_orders_js() {
    $action_slug = 'specific_name';
    ?>
    <script>
    jQuery(function($){
        $('a.<?php echo $action_slug; ?>').each( function(){
           /* $(this).attr('target','_blank');*/
        })
    });
    </script>
    <?php
}