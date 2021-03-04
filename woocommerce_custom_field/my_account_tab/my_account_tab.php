<?php 

/**
 * @snippet       WooCommerce Add New Tab @ My Account
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.5.7
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// ------------------
// 1. Register new endpoint to use for My Account page
// Note: Resave Permalinks or it will give 404 error
  
function bbloomer_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'premium-support', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'bbloomer_add_premium_support_endpoint' );
  
  
// ------------------
// 2. Add new query var
  
function bbloomer_premium_support_query_vars( $vars ) {
    $vars[] = 'premium-support';
    return $vars;
}
  
add_filter( 'query_vars', 'bbloomer_premium_support_query_vars', 0 );
  
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function bbloomer_add_premium_support_link_my_account( $items ) {
    // $items['premium-support'] = 'Premium Support';
    return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );
  
  
// ------------------
// 4. Add content to the new endpoint
  
function bbloomer_premium_support_content() {
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
 

 
if($uri_segments[6]){
	global $wpdb;
	$sub_order_id = $uri_segments[7];
	 
	$get_query = "select * from ".$wpdb->prefix."subscribe_order where id = '$sub_order_id'";
	$getall = $wpdb->get_results($get_query);
	
  
  $backurl  = home_url('/my-account/premium-support/'.$uri_segments[5]);
    $das = date('l');
	if($getall[0]->status == 2){?>
		<script>window.location.href = "<?=$backurl;?>";</script>
	<?php } 
	
	if($_POST){
		
		
	
	
	
		
		if(!empty($_POST['upqty'])){
		 $product = null;
		 $c = 0;
		 $product ='<table >';
	 foreach($_POST['upqty'] as $key => $p){
		
		if($p > 0){ 
		//if($c > 0){ $product .= ' | ';} 
		$product .=  '<tr><td style="padding: 0;">'.$key; 
		$product .= '<b>'. $p .'KG </b></td></tr>';
		$c++;
		}
		
		
	 }
	 $product .='</table>';
	 
	 /*** update**/
	  $wpdb->update( 
                                        $wpdb->prefix."subscribe_order", 
                                        array( 
                                                'products' => $product,
              									'products_details'	=> json_encode($_POST['upqty']) 		
                                        ), 
                                        array( 'id' => $sub_order_id), 
										array( 
                                                '%s' 	
                                        ), 
                                        array( '%d' ) 
                                );
	 /****/
	 }
		
	}
	
	 
	 
	
    $product = wc_get_product( $uri_segments[6]);
	$custom  = $product->get_meta( 'custom_text_field_title' );
	$product_limit  = $product->get_meta('custom_product_limit');
	
	$get_query = "select * from ".$wpdb->prefix."subscribe_order where id = '$sub_order_id'";
	$getall = $wpdb->get_results($get_query);
	$allorderprouct = json_decode($getall[0]->products_details); 
	
	$allproduct = json_decode($custom);
	 
	 $custom;
	echo "<h1>".$product->get_name()."</h1>";
	
	 if (count($allproduct) > 0 ) 
  { 
	
$allroow .= '<form action="" method="post"><table id="qutyc"><tr>
	<td>Img</td>
	<td>Name</td>
	<td>Qty</td>
	</tr>';	
if(!empty($allproduct)){	
	 foreach($allproduct as $key => $al){
		 $val = 0;
		
		$product = wc_get_product( $al ); 
		$ids = $product->get_id();
     
	 foreach($allorderprouct as $akey => $v){
			if(trim($akey) == trim($product->get_name())){  $val = $v;}
		 }
		 
		 
    $image = wp_get_attachment_image_src( get_post_thumbnail_id($ids ), 'single-post-thumbnail' );
    
	$svalu = null;
	if($key == 0){$svalu =  $product_limit;}
	$name = $product->get_name();
	$allroow .= '<tr>
	 
	<td> <img src="'. $image[0] .'"  style="width:50px" ></td>
	<td>'.$product->get_name() .'</td>
	<td><input type="number" id="cqty'.$key.'"  onkeyup="countupdate(this.value)"    name="upqty[ '.$name.' ]" style="width:86px" value="'.$val.'" requied ></td>
	</tr> ';	 
	 }
} 
  
	  $allroow .= '</table><button type="submit" class="single_add_to_cart_button">Update</button> <a  class="button" href="'.$backurl.'">Back</a> <input type="hidden" id="validte" value="'.$product_limit.'">
	  <span id="verror"></span>
	  </form>
	  ';
      printf(
            '<div><label>%s</label> 
			'. $allroow .'
			</div>',
            esc_html($custom_fields_woocommerce_title)
      );
       
  }
  
  
	
}else{

$orderid = $uri_segments[5]; // for www.example.com/user/account you will get 'user'
	global $wpdb;
	 $query = "SELECT * FROM ".$wpdb->prefix."subscribe_order where invoice = '$orderid'  ";
	$rowsCount=$wpdb->get_results($query);
	
	 
	?>
  <h1> Subscribe Order </h1>
  
  <table width="100%" border="0" cellspacing="0" cellpadding="10" class="mytable">
 <thead>
  <tr >
    <td bgcolor="#E8E8E8" width="5%"><strong>S.No.</strong></td>
    <td bgcolor="#E8E8E8" width="33%"><strong>Products</strong></td>
    <td bgcolor="#E8E8E8" width="20%"><strong>Days</strong></td>
    <td bgcolor="#E8E8E8" width="20%"><strong>Status</strong></td>
	<td bgcolor="#E8E8E8" width="20%"><strong>Action</strong></td>
  </tr>
  </thead>
  <tbody>
  <?php $sno = 1; $cou=1; foreach($rowsCount as $key => $ro){$key++; $sno++;
if($ro->subscribe_limit != 0 ){
	$cou = 1;
	$sno = 1;
  ?>   
   <tr>
    <td colspan="5"><h2>
	<?php $product = wc_get_product( $ro->product_id );
   echo $product->get_title();?></h2> </td>
  </tr>
<?php } ?>
   <tr>
    <td valign="top" ><?= $sno;?></td>
    <td valign="top" class="bgnone" ><?= $ro->products; ?></td>
    <td valign="top" ><?= ucfirst($ro->day); ?> <br> <?= ucfirst($ro->city); ?></td>
    <td valign="top" ><?php if($ro->status== 1){echo 'Pending';}elseif($ro->status== 2){echo 'Delivered';} ?></td>
	<?php if($ro->status== 2){ ?>
	<td valign="top" > - </td>
	<?php }elseif($ro->status== 1){ 
	     
      $timestamp = strtotime($ro->day);
	  $date = strtotime(date('d-m-Y', $timestamp));
	  $cdate = strtotime(date('d-m-Y'));
	  
       // if(strtolower($ro->day) == strtolower($da) && $cou == 1 && $date <= $cdate ){ $cou++
       
        if($date < $cdate ){
 		?>
<td valign="top" > - </td>
<?php }else{ ?>
	<td valign="top" ><a href="<?=$uri_path.'/'.$ro->product_id.'/'.$ro->id;?>">   Edit</a></td>
<?php }} ?>
  </tr>
  <?php } ?>
  </tbody>
</table>

<?php }}
  
add_action( 'woocommerce_account_premium-support_endpoint', 'bbloomer_premium_support_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format































/******** Hide item meta **/

function kia_hide_mnm_meta_in_emails( $meta ) {
    if( ! is_admin() ) {
        $criteria = array(  'key' => 'Products_in_json' );
        $meta = wp_list_filter( $meta, $criteria, 'NOT' );
    }
    return $meta;
}
add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'kia_hide_mnm_meta_in_emails' );











































