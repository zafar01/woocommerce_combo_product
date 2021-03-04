<?php 

/****************************** ***************
Adding custom values to the cart
****************************** ***************/

function cfwc_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
 if( ! empty( $_POST['cqty'] ) ) { // Add the item data
  $cart_item_data['title_field'] = json_encode($_POST['cqty']);
  $cart_item_data['delivery_date'] =  ($_POST['delivery_date']);
  $cart_item_data['gift_delivery_date'] =  ($_POST['gift_delivery_date']);
  $cart_item_data['product_type'] =  ($_POST['product_type']);
  $cart_item_data['Delivery_City'] =  ($_POST['Delivery_City']);
   
 }
 return $cart_item_data;
}

add_filter( 'woocommerce_add_cart_item_data', 'cfwc_add_custom_field_item_data', 10, 4 );


/****************************** *************** 
Displaying custom fields in the cart and checkout
****************************** ***************/

 
function cfwc_cart_item_name( $name, $cart_item, $cart_item_key ) {
 if( isset( $cart_item['title_field'] ) ) {
	 $allp = json_decode($cart_item['title_field']);
	 
	 $product_type = $cart_item['product_type'];
	 $Delivery_City = $cart_item['Delivery_City'];
	 
	 
	  
	 
	 if(!empty($allp)){
		 $product = null;
		 $c = 0;
		 $product ='<table >';
	 foreach($allp as $key => $p){
		
		if($p > 0){ 
		//if($c > 0){ $product .= ' | ';} 
		$product .=  '<tr><td style="padding: 0;">'.$key; 
		$product .= '<b>'. $p .'KG </b></td></tr>';
		$c++;
		}
		
		
	 }
	 $product .='</table>';
	 }
	 if($product_type){
	 $name .= sprintf('<p style="margin:0">%s</p>',$product_type);
	 }
	 if($Delivery_City){
	 $name .= sprintf('<p style="margin:0">%s</p>',$Delivery_City);
	 }
	 
 $name .= sprintf('<p style="margin:0">%s</p>',  $product
  //esc_html( $product  )
 );
 }
 return $name;
}
add_filter( 'woocommerce_cart_item_name', 'cfwc_cart_item_name', 10, 3 );



/****************************** *************** 
Displaying custom fields in the WooCommerce order and email confirmations
****************************** ***************/
 
 
function cfwc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	 
	
 foreach( $item as $cart_item_key=>$values ) {
	 
	if($values['product_type']){
		 $product_type = $values['product_type'];
	 }
	 if($values['Delivery_City']){
		 $dcity = $values['Delivery_City'];
	 }
	  
	 if($values['delivery_date']){
		 $delivery_date = $values['delivery_date'];
	 }
	 
	 if($values['gift_delivery_date']){
		 $gift_delivery_date = $values['gift_delivery_date'];
	 }
	 
 if( isset( $values['title_field'] ) ) {
	 
	 $allp = json_decode($values['title_field']);
	 if(!empty($allp)){
		 $product = '<table cellspacing="0" cellpadding="0">';
		 $c = 0;
	 foreach($allp as $key => $p){
		if($p > 0){ 
		 
		$product .=  '<tr><td style="margin:0;padding: 0;">'.$key; 
		$product .= '<b>'. $p .'KG </b></td></tr>';
		$c++;
		}
	 }
	 }
	 $product .='</table>';
  if($dcity){	 
  $item->add_meta_data( __( 'Delivery City', 'cfwc' ), $dcity, true );
  }
   
  
  if($delivery_date){
  $item->add_meta_data( __( 'Delivery date', 'cfwc' ), $delivery_date, true );
  }
  
  if($gift_delivery_date){
  $item->add_meta_data( __( 'Gift Delivery date', 'cfwc' ), $gift_delivery_date, true );
  }

  
  $item->add_meta_data( __( 'Product Type', 'cfwc' ), $product_type, true );
  $item->add_meta_data( __( 'Products in combo', 'cfwc' ), $product, true );
  $item->add_meta_data( __( 'Products_in_json', 'cfwc' ), $values['title_field'], true );
  
 }
 }
}
 add_action( 'woocommerce_checkout_create_order_line_item', 'cfwc_add_custom_data_to_order', 10, 4 );




