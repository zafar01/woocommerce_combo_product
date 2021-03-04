<?php
      
   
    
    register_deactivation_hook(__FILE__,'vns_vertical_news_remove_access_capabilities');
     
   
    add_filter('widget_text', 'do_shortcode');
    /* Add our function to the widgets_init hook. */
    add_action( 'widgets_init', 'verticalScrollSet' );

    add_action('plugins_loaded', 'vns_load_lang_for_subscribe_order');
   // add_action('wp_enqueue_scripts', 'news_scroller_load_styles_and_js');


    add_action( 'upgrader_process_complete',  'vns_subscribe_order_upgrader_process_complete', 10, 4 );

    function vns_load_lang_for_subscribe_order() {

            load_plugin_textdomain( 'subscribe_order_elitbuzz', false, basename( dirname( __FILE__ ) ) . '/languages/' );
            add_filter( 'map_meta_cap',  'map_vns_subscribe_order_meta_caps', 10, 4 );
            add_filter( 'user_has_cap', 'vns_subscribe_order_list' , 10, 4 );
    }
    
    function vns_subscribe_order_list($allcaps, $caps, $args, $user){
        
        
        if ( ! in_array( 'administrator', $user->roles ) ) {
            
            return $allcaps;
        }
        else{
            
            if(!isset($allcaps['vns_subscribe_order_view_news'])){
                
                $allcaps['vns_subscribe_order_view_news']=true;
            }
            
            if(!isset($allcaps['vns_subscribe_order_add_news'])){
                
                $allcaps['vns_subscribe_order_add_news']=true;
            }
            
            if(!isset($allcaps['vns_subscribe_order_edit_news'])){
                
                $allcaps['vns_subscribe_order_edit_news']=true;
            }
            
            if(!isset($allcaps['vns_subscribe_order_delete_news'])){
                
                $allcaps['vns_subscribe_order_delete_news']=true;
            }
            
        }
        
        return $allcaps;
    }
    function map_vns_subscribe_order_meta_caps( array $caps, $cap, $user_id, array $args  ) {
        
        
        if ( ! in_array( $cap, array( 
                                      'vns_subscribe_order_view_news',
                                      'vns_subscribe_order_add_news',
                                      'vns_subscribe_order_edit_news',
                                      'vns_subscribe_order_delete_news'
                                    ), true ) ) {
            
			return $caps;
         }

       

   
        $caps = array();

        switch ( $cap ) {
              
              
                case 'vns_subscribe_order_view_news':
                        $caps[] = 'vns_subscribe_order_view_news';
                        break;
              
                case 'vns_subscribe_order_add_news':
                        $caps[] = 'vns_subscribe_order_add_news';
                        break;
              
                case 'vns_subscribe_order_edit_news':
                        $caps[] = 'vns_subscribe_order_edit_news';
                        break;
              
                case 'vns_subscribe_order_delete_news':
                        $caps[] = 'vns_subscribe_order_delete_news';
                        break;
              
                default:
                        
                        $caps[] = 'do_not_allow';
                        break;
        }

      
     return apply_filters( 'vns_subscribe_order_map_meta_caps', $caps, $cap, $user_id, $args );
}
    

function vns_subscribe_order_add_access_capabilities() {
     
    // Capabilities for all roles.
    $roles = array( 'administrator','editor' );
    foreach ( $roles as $role ) {
        
            $role = get_role( $role );
            if ( empty( $role ) ) {
                    continue;
            }
         
            
          
            
            if(!$role->has_cap( 'vns_subscribe_order_view_news' ) ){
            
                    $role->add_cap( 'vns_subscribe_order_view_news' );
            }
            
            if(!$role->has_cap( 'vns_subscribe_order_add_news' ) ){
            
                    $role->add_cap( 'vns_subscribe_order_add_news' );
            }
            
            if(!$role->has_cap( 'vns_subscribe_order_edit_news' ) ){
            
                    $role->add_cap( 'vns_subscribe_order_edit_news' );
            }
            
            if(!$role->has_cap( 'vns_subscribe_order_delete_news' ) ){
            
                    $role->add_cap( 'vns_subscribe_order_delete_news' );
            }
            
            
         
    }
    
    $user = wp_get_current_user();
    $user->get_role_caps();
    
}
      function news_scroller_load_styles_and_js(){
          
       // if (!is_admin()) 
		{                                                       

            wp_register_style( 'news-style', plugins_url('/css/newsscrollcss.css', __FILE__) );
            wp_register_script('newscript',plugins_url('/js/jv.js', __FILE__),array(),'2.0');
 wp_localize_script( 'ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        }  
    }   

    function vns_table_column_exists( $table_name, $column_name ) {
       
	global $wpdb;
	$column = $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
		DB_NAME, $table_name, $column_name
	) );
	if ( ! empty( $column ) ) {
		return true;
	}
	return false;
        
  } 
  
 



   

    function vns_subscribe_order_upgrader_process_complete(){
        
        vns_subscribe_order_add_access_capabilities();
    }
    
     function vns_vertical_news_remove_access_capabilities() {
         
            global $wp_roles;

            if ( ! isset( $wp_roles ) ) {
                    $wp_roles = new WP_Roles();
            }

            foreach ( $wp_roles->roles as $role => $details ) {
                    $role = $wp_roles->get_role( $role );
                    if ( empty( $role ) ) {
                            continue;
                    }

                    $role->remove_cap( 'vns_subscribe_order_view_news' );
                    $role->remove_cap( 'vns_subscribe_order_add_news' );
                    $role->remove_cap( 'vns_subscribe_order_edit_news' );
                    $role->remove_cap( 'vns_subscribe_order_delete_news' );

            }

            // Refresh current set of capabilities of the user, to be able to directly use the new caps.
            $user = wp_get_current_user();
            $user->get_role_caps();
    }

    

   

    /* Function that registers our widget. */
    function verticalScrollSet() {
        register_widget( 'verticalScroll' );
    }


    function subscribe_order_function(){

        $action='gridview';
        global $wpdb;


        if(isset($_GET['action']) and $_GET['action']!=''){


            $action=sanitize_text_field(trim($_GET['action']));
        }

        if(strtolower($action)==strtolower('gridview')){ 

            if ( ! current_user_can( 'vns_subscribe_order_view_news' ) ) {
                
                wp_die( __( "Access Denied", "subscribe_order_elitbuzz" ) );
            }


        ?> 
        <div id="poststuff">
            
            

            <?php 

                $messages=get_option('scrollnews_messages'); 
                $type='';
                $message='';
                if(isset($messages['type']) and $messages['type']!=""){

                    $type=sanitize_text_field($messages['type']);
                    $message=sanitize_text_field($messages['message']);

                }  


               if(trim($type)=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
               else if(trim($type)=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}
      

                update_option('scrollnews_messages', array());     
            ?>

            <div id="post-body" class="metabox-holder columns-2">  
                <div id="post-body-content" >
                    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                    <h1><?php echo __("Subscribe Order",'subscribe_order_elitbuzz');?>&nbsp;&nbsp;  </h1>
                    <br/> 
  			
 
                    <form method="POST" action="admin.php?page=subscribe-order-slug&action=deleteselected" id="posts-filter" onkeypress="return event.keyCode != 13;">


                       
                        
                        <br class="clear">
                         <?php
                                $setacrionpage='admin.php?page=subscribe-order-slug';

                                if(isset($_GET['order_by']) and $_GET['order_by']!=""){
                                  $setacrionpage.='&order_by='.sanitize_text_field($_GET['order_by']);   
                                }

                                if(isset($_GET['order_pos']) and $_GET['order_pos']!=""){
                                 $setacrionpage.='&order_pos='.sanitize_text_field($_GET['order_pos']);   
                                }

                                $seval="";
                                if(isset($_GET['search_term']) and $_GET['search_term']!=""){
                                 $seval=trim(sanitize_text_field($_GET['search_term']));   
                                }
                                

                            ?>
                        <?php 

                                $order_by='id';
                                $order_pos="desc";

                                if(isset($_GET['order_by']) and sanitize_sql_orderby($_GET['order_by'])!==false){

                                   $order_by=trim($_GET['order_by']); 
                                }

                                if(isset($_GET['order_pos'])){

                                   $order_pos=trim(sanitize_text_field($_GET['order_pos'])); 
                                }
                                 $search_term='';
                                if(isset($_GET['search_term'])){

                                   $search_term= sanitize_text_field($_GET['search_term']);
                                }
                                
                                $search_term_='';
                                if(isset($_GET['search_term'])){

                                   $search_term_='&search_term='.urlencode(sanitize_text_field($_GET['search_term']));
                                }
                                
                                $query="SELECT * FROM ".$wpdb->prefix."subscribe_order";
                                $queryCount="SELECT count(*) FROM ".$wpdb->prefix."subscribe_order";
                                if($search_term!=''){
                                  $query.=" where ( invoice like '%$search_term%' or day like '%$search_term%' ) "; 
                                  $queryCount.=" where ( invoice like '%$search_term%' or day like '%$search_term%' ) "; 
                                }

                                $order_by=sanitize_text_field(sanitize_sql_orderby($order_by));
                                $order_pos=sanitize_text_field(sanitize_sql_orderby($order_pos));

                                $query.=" order by $order_by $order_pos";
                                $rowsCount=$wpdb->get_var($queryCount);

                          ?>
                          <div style="padding-top:5px;padding-bottom:5px">
                                <b><?php echo __( 'Search','subscribe_order_elitbuzz');?> : </b>
                                  <input type="text" value="<?php echo $seval;?>" id="search_term" name="search_term">&nbsp;
                                  <input type='button'  value='<?php echo __( 'Search','subscribe_order_elitbuzz');?>' name='searchusrsubmit' class='button-primary' id='searchusrsubmit' onclick="SearchredirectTO();" >&nbsp;
                                  <input type='button'  value='<?php echo __( 'Reset Search','subscribe_order_elitbuzz');?>' name='searchreset' class='button-primary' id='searchreset' onclick="ResetSearch();" >
                            </div>  
                            <script type="text/javascript" >
                                jQuery('#search_term').on("keyup", function(e) {
                                       if (e.which == 13) {

                                           SearchredirectTO();
                                       }
                                  });   
                             function SearchredirectTO(){
                               var redirectto='<?php echo $setacrionpage; ?>';
                               var searchval=jQuery('#search_term').val();
                               redirectto=redirectto+'&search_term='+jQuery.trim(encodeURIComponent(searchval));  
                               window.location.href=redirectto;
                             }
                            function ResetSearch(){

                                 var redirectto='<?php echo $setacrionpage; ?>';
                                 window.location.href=redirectto;
                                 exit;
                            }
                            </script>
							
							<div id="succesd" style="display:none; margin-bottom:10px; background:rgb(254 255 244); border:1px solid #04ca04; color:green;padding:5px 10px;"></div>
							<div id="orrderload" style="background: #000;position: absolute;width:80%; height:40%; text-align:center; opacity: 0.5; display:none">
							<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/loader.gif'; ?>"></div>
							
                        <div id="no-more-tables" style="display:none">
                            <table cellspacing="0" id="gridTbl" class="table-bordered table-striped table-condensed cf " >
                                <thead>
                                    <tr>
                                        <th class="manage-column column-cb check-column" scope="col"> </th>
                                         <?php if($order_by=="invoice" and $order_pos=="asc"):?>
                                            <th class="alignLeft"><a href="<?php echo $setacrionpage;?>&order_by=invoice&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Order','subscribe_order_elitbuzz');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                        <?php else:?>
                                            <?php if($order_by=="invoice"):?>
                                                <th class="alignLeft"><a href="<?php echo $setacrionpage;?>&order_by=invoice&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Order','subscribe_order_elitbuzz');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                            <?php else:?>
                                                <th class="alignLeft"><a href="<?php echo $setacrionpage;?>&order_by=invoice&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Order','subscribe_order_elitbuzz');?></a></th>
                                            <?php endif;?>    
                                        <?php endif;?> 
                                        <?php if($order_by=="day" and $order_pos=="asc"):?>
                                            <th><a href="<?php echo $setacrionpage;?>&order_by=day&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Day','subscribe_order_elitbuzz');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                        <?php else:?>
                                            <?php if($order_by=="day"):?>
                                                <th><a href="<?php echo $setacrionpage;?>&order_by=day&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Day','subscribe_order_elitbuzz');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                            <?php else:?>
                                                <th><a href="<?php echo $setacrionpage;?>&order_by=day&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Day','subscribe_order_elitbuzz');?></a></th>
                                            <?php endif;?>    
                                        <?php endif;?>
                                        <th> Products</th>
                                        <th> Status </th>
                                    </tr> 
                                </thead>

                                <tbody id="the-list">
                                    <?php
                                      
                                        if($rowsCount > 0){

                                            global $wp_rewrite;
                                            $rows_per_page = 10;

                                            $current = (isset($_GET['paged'])) ? intval($_GET['paged']) : 1;
                                            $pagination_args = array(
                                                'base' => @add_query_arg('paged','%#%'),
                                                'format' => '',
                                                'total' => ceil($rowsCount/$rows_per_page),
                                                'current' => $current,
                                                'show_all' => false,
                                                'type' => 'plain',
                                            );


                                            $offset = ($current - 1) * $rows_per_page;

                                            $query.=" limit $offset, $rows_per_page  ";
                                            $rows = $wpdb->get_results ( $query,ARRAY_A);
                                            $delRecNonce=wp_create_nonce('delete_news');
                                            foreach($rows as $k => $row ) {$k++; 

                                                 
                                                $id=$row['id'];
                                                $editlink="admin.php?page=subscribe-order-slug&action=addedit&id=$id";
                                                $deletelink="admin.php?page=subscribe-order-slug&action=delete&id=$id&nonce=$delRecNonce";

                                            ?>
                                            <tr valign="top" >
                                                <td class="alignCenter check-column"> <?= $k;?> </td>
                                                <td class=""   data-title="<?php echo __('Name','subscribe_order_elitbuzz'); ?>" ><strong><a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?=$row['invoice'];?>&action=edit" ><?php echo stripslashes_deep($row['invoice']) ?></a></strong></td>  
                                                <td class=" "   data-title="<?php echo __('Day','subscribe_order_elitbuzz'); ?>"><span><?php echo ucfirst($row['day']) ;?>
<br>											<?=	ucfirst($row['city']); ?></span></td>
                                                
												 
												<td class="alignCenter"   data-title="<?php echo __('Products','subscribe_order_elitbuzz'); ?>"><strong>
												
												<?php echo $row['products']; ?>
												
												</td> 
												<td class="alignCenter"   data-title="<?php echo __('Status','subscribe_order_elitbuzz'); ?>"> 
												<input type="hidden" value="<?=$row['id'];?>" id="order<?=$row['id'];?>">
												<select onchange="pendingdis(this.value,document.getElementById('order<?=$row['id'];?>').value)" >
												<option value="1" <?php if($row['status']== 1){echo 'selected';}?>>Pending</option>
												<option value="2" <?php if($row['status']== 2){echo 'selected';}?>>Delivered</option>
												</select>
												
												
												 </td>  
												
												 
												 												
                                                
                                            </tr>

                                            <?php 
                                            } 
                                        }
                                        else{
                                        ?>

                                        <tr valign="top" class="" id="">
                                            <td colspan="5" data-title="<?php echo __('No Record','subscribe_order_elitbuzz'); ?>" align="center"><strong><?php echo __("No Coupon Found",'subscribe_order_elitbuzz');?></strong></td>  
                                        </tr>
                                        <?php 
                                        } 
                                    ?>      
                                </tbody>
                            </table>
                        </div>
                       <?php
                            if($rowsCount>0){

                                echo "<div class='pagination' style='padding-top:10px'>";
                                echo paginate_links($pagination_args);
                                echo "</div>";
                            }
                        ?>
                        <br/>
                        
                         
                    </form>
                     

                    <br class="clear">
                </div>
                

            </div>  
        </div>  

        <?php 
        }   
        else if(strtolower($action)==strtolower('addedit')){
        ?>
        <br/>

        <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/wordpress-subscribe_order_elitbuzz-pro/"><?php echo __('UPGRADE TO PRO VERSION','subscribe_order_elitbuzz'); ?></a></h3></span>
        <?php        
            if(isset($_POST['btnsave'])){

                 if(!check_admin_referer( 'action_news_add_edit','add_edit_nonce' )){
                
                        wp_die('Security check fail'); 
                   }
                   
                //edit save
                if(isset($_POST['newsid'])){

                    //add new

                    if ( ! current_user_can( 'vns_subscribe_order_edit_news' ) ) {
                
                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','subscribe_order_elitbuzz');
                        update_option('scrollnews_messages', $scrollnews_messages);
                        $location="admin.php?page=subscribe-order-slug";
                        echo "<script type='text/javascript'> location.href='$location';</script>";
                        exit;


                    } 
                    
                    $title=trim(htmlentities(sanitize_text_field($_POST['newstitle']),ENT_QUOTES));
                    $newsurl=trim(htmlentities(esc_url_raw($_POST['newsurl']),ENT_QUOTES));
                    $contant=trim(strip_tags($_POST['newscont'],'<br><a><b><i><span><h1><h2><h3><h4><h5><h6><hr><p><ul><li>'));
                    $newsId=intval(htmlentities(sanitize_text_field($_POST['newsid']),ENT_QUOTES));

                    $location='admin.php?page=subscribe-order-slug';

                    try{
                        
                        $wpdb->update( 
                                        $wpdb->prefix."subscribe_order", 
                                        array( 
                                                'Coupon_code' => $title 
                                        ), 
                                        array( 'id' => $newsId, 'status' => 1 ), 
                                        array( 
                                                '%s',	
                                                '%s'	
                                        ), 
                                        array( '%d' ) 
                                );
                        
                 
                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='succ';
                        $scrollnews_messages['message']='News updated successfully.';
                        update_option('scrollnews_messages', $scrollnews_messages);


                    }
                    catch(Exception $e){

                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']='Error while updating news.';
                        update_option('scrollnews_messages', $scrollnews_messages);
                    }  

                    echo "<script> location.href='$location';</script>";
                }
                else{

                    //add new

                    if ( ! current_user_can( 'vns_subscribe_order_add_news' ) ) {
                
                        
                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','subscribe_order_elitbuzz');
                        update_option('scrollnews_messages', $scrollnews_messages);
                        $location="admin.php?page=subscribe-order-slug";
                        echo "<script type='text/javascript'> location.href='$location';</script>";
                        exit;
                        
                    } 
                    
                    $title=trim(htmlentities(sanitize_text_field($_POST['newstitle']),ENT_QUOTES));
                    $newsurl=trim(htmlentities(sanitize_text_field($_POST['newsurl']),ENT_QUOTES));
                    $contant=trim(strip_tags($_POST['newscont'],'<br><a><b><i><span><h1><h2><h3><h4><h5><h6><hr><p><ul><li>'));
                    /*
                    $createdOn=@date( 'Y-m-d H:i:s', current_time( 'mysql' ));
                    if(get_option('time_format')=='H:i')
                        $createdOn=date('Y-m-d H:i:s',strtotime(current_time('mysql')));
                    else   
                        $createdOn=date('Y-m-d h:i:s',strtotime(current_time('mysql')));
                     * 
                     */
                    
                    $createdOn=current_time('mysql');

                        
                    $location='admin.php?page=subscribe-order-slug';

                    try{
                        
						 $query="SELECT * FROM ".$wpdb->prefix."uae24x7_coupon WHERE Coupon_code=$title";
                                    $myrow  = $wpdb->get_row($query);

                                    if(is_object($myrow)){ $locations='admin.php?page=subscribe-order-slug&action=addedit&try=1';
									echo "<script> location.href='$locations';</script>";    
                                }else{
                        
                        $wpdb->insert( 
                                $wpdb->prefix."subscribe_order", 
                                array( 
                                        'Coupon_code' => $title, 
                                        'status' => 1, 
                                        'createdon' => $createdOn  
                                ), 
                                array( 
                                        '%s', 
                                        '%s', 
                                        '%s', 
                                        '%s', 
                                ) 
                        );
                    }
                    
                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='succ';
                        $scrollnews_messages['message']=__("New news added successfully.",'subscribe_order_elitbuzz');
                        update_option('scrollnews_messages', $scrollnews_messages);


                    }
                    catch(Exception $e){

                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']=__("Error while adding news.",'subscribe_order_elitbuzz');
                        update_option('scrollnews_messages', $scrollnews_messages);
                    }  

                    echo "<script> location.href='$location';</script>";          

                } 

            }
            else{ 

            ?>
            
            
            <?php 
            } 
        }else if(strtolower($action)==strtolower('delete')){

             $retrieved_nonce = '';
            
            if(isset($_GET['nonce']) and $_GET['nonce']!=''){
              
                $retrieved_nonce=sanitize_text_field($_GET['nonce']);
                
            }
            if (!wp_verify_nonce($retrieved_nonce, 'delete_news' ) ){
        
                
                wp_die('Security check fail'); 
            }
                
            if ( ! current_user_can( 'vns_subscribe_order_delete_news' ) ) {

                $scrollnews_messages=array();
                $scrollnews_messages['type']='err';
                $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','subscribe_order_elitbuzz');
                update_option('scrollnews_messages', $scrollnews_messages);
                $location="admin.php?page=subscribe-order-slug";
                echo "<script type='text/javascript'> location.href='$location';</script>";
                exit;
            } 
            
            $location='admin.php?page=subscribe-order-slug';
            $deleteId=intval(htmlentities(strip_tags($_GET['id']),ENT_QUOTES));

            try{
                
                    $wpdb->query( 
                               $wpdb->prepare( 
                                       "
                                       DELETE FROM ".$wpdb->prefix."uae24x7_coupon
                                        WHERE id = %d and status = 1",
                                       $deleteId 
                               )
                       );



                $scrollnews_messages=array();
                $scrollnews_messages['type']='succ';
                $scrollnews_messages['message']=__('Coupon deleted successfully.','subscribe_order_elitbuzz');
                update_option('scrollnews_messages', $scrollnews_messages);


            }
            catch(Exception $e){

                $scrollnews_messages=array();
                $scrollnews_messages['type']='err';
                $scrollnews_messages['message']=__('Error while deleting Coupon.','subscribe_order_elitbuzz');
                update_option('scrollnews_messages', $scrollnews_messages);
            }  

            echo "<script> location.href='$location';</script>";

        }  
        else if(strtolower($action)==strtolower('deleteselected')){

             if(!check_admin_referer('action_news_mass_delete','mass_delete_nonce')){
               
                wp_die('Security check fail'); 
            }
            
            if ( ! current_user_can( 'vns_subscribe_order_delete_news' ) ) {

                $scrollnews_messages=array();
                $scrollnews_messages['type']='err';
                $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','subscribe_order_elitbuzz');
                update_option('scrollnews_messages', $scrollnews_messages);
                $location="admin.php?page=subscribe-order-slug";
                echo "<script type='text/javascript'> location.href='$location';</script>";
                exit;
            } 
            
            $location='admin.php?page=subscribe-order-slug'; 
            if(isset($_POST) and isset($_POST['deleteselected']) and  ( sanitize_text_field($_POST['action'])=='delete' or sanitize_text_field($_POST['action_upper'])=='delete')){

                if(sizeof($_POST['news']) >0){

                    $deleteto=$_POST['news'];
                    
                        try{

                            if(is_array($deleteto)){
                                
                                foreach ($deleteto as $deleteId){

                                    $deleteId=intval($deleteId);

                                     $wpdb->query( 
                                                $wpdb->prepare( 
                                                        "
                                                        DELETE FROM ".$wpdb->prefix."uae24x7_coupon
                                                         WHERE id = %d and status = 1",
                                                        $deleteId 
                                                )
                                        );



                                }  
                                
                            }
                            $scrollnews_messages=array();
                            $scrollnews_messages['type']='succ';
                            $scrollnews_messages['message']=__('selected Coupon deleted successfully.','subscribe_order_elitbuzz');
                            update_option('scrollnews_messages', $scrollnews_messages);


                        }
                        catch(Exception $e){

                            $scrollnews_messages=array();
                            $scrollnews_messages['type']='err';
                            $scrollnews_messages['message']=__('Error while deleting Coupon.','subscribe_order_elitbuzz');
                            update_option('scrollnews_messages', $scrollnews_messages);
                        }  

                        echo "<script> location.href='$location';</script>";exit;


                }
                else{

                    echo "<script> location.href='$location';</script>";   
                }

            }
            else{

                echo "<script> location.href='$location';</script>";      
            }

        }    
    }
    
    

    class verticalScroll extends WP_Widget {

        function __construct() {

            $widget_ops = array('classname' => 'verticalScroll', 'description' => 'Coupon');
            parent::__construct('verticalScroll', 'Coupon',$widget_ops);
        }

        function widget( $args, $instance ) {
            global $wpdb;
            
            if(is_array($args)){

                extract( $args );
            }

            wp_enqueue_style('news-style');
            wp_enqueue_script('jquery');
            wp_enqueue_script('newscript');

        
            $title = apply_filters('widget_title', empty( $instance['title'] ) ? 'News Scroll' :$instance['title']);   
            include_once(ABSPATH . WPINC . '/feed.php');
            echo @$before_widget;
            echo @$before_title.$title.$after_title;   
            $maxitem=empty( $instance['maxitem'] ) ? 5 :intval($instance['maxitem']); 
            $padding=empty( $instance['padding'] ) ? 5 :intval($instance['padding']); 
            $add_link_to_title=intval(($instance['add_link_to_title']==null) ? 0 :$instance['add_link_to_title']); 
            $show_content=intval(($instance['show_content']==null) ? 0 :$instance['show_content']); 
            $delay=empty( $instance['delay'] ) ? 5 :intval($instance['delay']); 
            $modern_scroller_delay=empty( $instance['modern_scroller_delay'] ) ? 5000 :intval($instance['modern_scroller_delay']); 
            $height=empty( $instance['height'] ) ? 200 :intval($instance['height']); 
            $scrollamt=empty( $instance['scrollamount'] ) ? 1 :intval($instance['scrollamount']); 
            $modern_speed=empty( $instance['modern_speed'] ) ? 1700 :intval($instance['modern_speed']); 
            $s_type=empty( $instance['s_type'] ) ? 'classic' :sanitize_text_field($instance['s_type']); 
            $direction=empty( $instance['direction'] ) ? 'up' :sanitize_text_field($instance['direction']); 

        
            $randomNum=rand(0,10000);
            $news_style='classic';
            
            $query="SELECT * FROM ".$wpdb->prefix."uae24x7_coupon order by day desc limit $maxitem";
            $rows=$wpdb->get_results($query,'ARRAY_A');
        ?>


        <?php if($s_type=='classic'){
                $news_style='classic';  
            }
            else if($s_type=='modern'){
                $news_style='modern';  
            }
        ?>
        <?php if($news_style=='classic'){ ?>  
            <marquee height='<?php echo $height; ?>' direction='<?php echo $direction;?>'  onmouseout="this.start()" onmouseover="this.stop()" scrolldelay="<?php echo $delay; ?>" scrollamount="<?php echo $scrollamt; ?>" direction="up" behavior="scroll" >
                <?php } ?>    
                <div id="news-container_<?php echo $randomNum; ?>" class="news-container" style="visibility: hidden">
                <?php if(!$show_content):?>
                 <style>.news-info{display:inline-block;}.news-img{padding-bottom: 20px}</style>
                <?php endif;?>
                <ul>
                <?php

                        foreach($rows as $row){
                        ?>
                        <li>
                            <div style="padding:<?php echo $padding; ?>px">
                                <div class="newsscroller_title"><?php if($add_link_to_title){?><a href='<?php echo $row['custom_link']; ?>'><?php } ?><?php echo  stripslashes_deep($row['Coupon_code']) ; ?><?php if($add_link_to_title){?></a><?php } ?></div>
                                <div style="clear:both"></div>
                                <?php if($show_content){ ?>
                                    <div class="scrollercontent">
                                        <?php echo nl2br(stripslashes_deep($row['content'])); ?>
                                    </div>
                                    <?php } ?>       
                            </div>
                             <div style="clear:both"></div>
                        </li>
                        <?php 
                        }

                    ?>
                </ul>
            </div>
            <?php if($news_style=='classic'){ ?>  
            </marquee>
            <?php } ?>
        <?php if($news_style=='modern'){ ?>
            <script type="text/javascript">
        
             <?php $intval= uniqid('interval_');?>
               
                    var <?php echo $intval;?> = setInterval(function() {

                    if(document.readyState === 'complete') {

                       clearInterval(<?php echo $intval;?>);
                        jQuery("#news-container_<?php echo $randomNum; ?>").css('visibility','visible');
                        jQuery(function(){
                                jQuery('#news-container_<?php echo $randomNum; ?>').vTicker({ 
                                        speed: <?php echo $modern_speed; ?>,
                                        pause: <?php echo $modern_scroller_delay; ?>,
                                        animation: '',
                                        mousePause: true,
                                        height:<?php echo $height; ?>,
                                        direction:'<?php echo $direction;?>'
                                });                                            
                        });

                        }    
                }, 100);
            </script>
            <?php
            }
            else { ?>
                
             <script type="text/javascript">
        
             <?php $intval= uniqid('interval_');?>
               
                    var <?php echo $intval;?> = setInterval(function() {

                    if(document.readyState === 'complete') {

                       clearInterval(<?php echo $intval;?>);
                        jQuery("#news-container_<?php echo $randomNum; ?>").css('visibility','visible');
                       

                        }    
                }, 100);
            </script>
           <?php 
            }

            echo $after_widget; 
        }



        function update( $new_instance, $old_instance ) {


            $instance = $old_instance;
            $instance['title'] = sanitize_text_field($new_instance['title']);
            $instance['add_link_to_title'] = intval($new_instance['add_link_to_title']);
            $instance['maxitem'] = intval($new_instance['maxitem']);
            $instance['padding'] = intval($new_instance['padding']);
            $instance['show_content'] = intval($new_instance['show_content']);
            $instance['delay'] = intval($new_instance['delay']);
            $instance['scrollamount'] = intval($new_instance['scrollamount']);
            $instance['height'] = intval($new_instance['height']);
            $instance['s_type'] = sanitize_text_field($new_instance['s_type']);
            $instance['modern_scroller_delay'] = sanitize_text_field($new_instance['modern_scroller_delay']);
            $instance['modern_speed'] = intval($new_instance['modern_speed']);
            $instance['direction'] = sanitize_text_field($new_instance['direction']);
            return $instance;


        }
        function form( $instance ) {

            //Defaults
            $instance = wp_parse_args( (array) $instance, array('s_type'=>'classic','title' => 'News','maxitem' => 5,'padding' => 5,'show_content' => 1,'delay'=>5,'scrollamount'=>1,'add_link_to_title'=>1,'height'=>200,'modern_scroller_delay'=>5000,'modern_speed'=>1700,'direction'=>'up'));
            $scroller_type=$instance['s_type'];
            $direction=$instance['direction'];

            $randomNum=rand(0,10000);
        ?>
        <?php

            global $wpdb;
     
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('s_type'); ?>"><b><?php echo __('News Scroller Type:','subscribe_order_elitbuzz'); ?></b></label><br/>
            <input <?php if($scroller_type=='modern'){?>checked="checked" <?php } ?> type="radio" name="<?php echo $this->get_field_name('s_type');?>" onchange="chnageParam(this);" id="s_type_modern" value="modern"> <?php echo __('Modern','subscribe_order_elitbuzz'); ?>
            <input <?php if($scroller_type=='classic'){?>checked="checked" <?php } ?> type="radio" name="<?php echo $this->get_field_name('s_type');?>" onchange="chnageParam(this);"  id="s_type_classic" value="classic"> <?php echo __('Classic','subscribe_order_elitbuzz'); ?>
        </p>
        

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><b><?php echo __('Title:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('add_link_to_title'); ?>" name="<?php echo $this->get_field_name('add_link_to_title'); ?>"
                type="checkbox" <?php checked($instance['add_link_to_title'], 1); ?> value="1" />
            <label for="<?php echo $this->get_field_id('add_link_to_title'); ?>"><b><?php echo __('Add link to Coupon title:','subscribe_order_elitbuzz'); ?></b></label>
        </p>
        <p><label for="<?php echo $this->get_field_id('maxitem'); ?>"><b><?php echo __('Max item from news:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('maxitem'); ?>" name="<?php echo $this->get_field_name('maxitem'); ?>"
                type="text" value="<?php echo $instance['maxitem']; ?>" />
        </p>

        <p><label for="<?php echo $this->get_field_id('height'); ?>"><b><?php echo __('Height of scroller:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $instance['height']; ?>" />px
        </p>

        <p><label for="<?php echo $this->get_field_id('padding'); ?>"><b><?php echo __('Padding:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('padding'); ?>" name="<?php echo $this->get_field_name('padding'); ?>" type="text" value="<?php echo $instance['padding']; ?>" />px
        </p>

        <p>
            <input id="<?php echo $this->get_field_id('show_content'); ?>" name="<?php echo $this->get_field_name('show_content'); ?>"
                type="checkbox" <?php checked($instance['show_content'], 1); ?> value="1" />
            <label for="<?php echo $this->get_field_id('show_content'); ?>"><b><?php echo __('Show news content:','subscribe_order_elitbuzz'); ?></b></label>
        </p>

        <p id='classic_delay_<?php echo $this->get_field_id('delay'); ?>' <?php if($scroller_type=='modern'){?>style="display:none" <?php }?>  ><label for="<?php echo $this->get_field_id('delay'); ?>"><b><?php echo __('Delay:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('delay'); ?>" name="<?php echo $this->get_field_name('delay'); ?>" type="text" value="<?php echo $instance['delay']; ?>" /><?php echo __('Micro Sec','subscribe_order_elitbuzz'); ?>
        </p>

        <p id='modern_delay_<?php echo $this->get_field_id('modern_scroller_delay'); ?>' <?php if($scroller_type=='classic'){?>style="display:none" <?php }?>  ><label for="<?php echo $this->get_field_id('delay'); ?>"><b><?php echo __('Delay:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('modern_scroller_delay'); ?>" name="<?php echo $this->get_field_name('modern_scroller_delay'); ?>" type="text" value="<?php echo $instance['modern_scroller_delay']; ?>" />
        </p>

        <p id='modern_speed_<?php echo $this->get_field_id('modern_speed'); ?>' <?php if($scroller_type=='classic'){?>style="display:none" <?php }?>  ><label for="<?php echo $this->get_field_id('modern_speed'); ?>"><b><?php echo __('Speed:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('modern_speed'); ?>" name="<?php echo $this->get_field_name('modern_speed'); ?>" type="text" value="<?php echo $instance['modern_speed']; ?>" />
        </p>
        <p id='classic_scrollamount_<?php echo $this->get_field_id('scrollamount'); ?>' <?php if($scroller_type=='modern'){?>style="display:none" <?php }?> ><label for="<?php echo $this->get_field_id('scrollamount'); ?>"><b><?php echo __('Scroll Amount:','subscribe_order_elitbuzz'); ?></b></label>
            <input class="widefat" id="<?php echo $this->get_field_id('scrollamount'); ?>" name="<?php echo $this->get_field_name('scrollamount'); ?>" type="text" value="<?php echo $instance['scrollamount']; ?>" /><?php echo __('(Ie 1,2,3)','subscribe_order_elitbuzz'); ?>
        </p>
         <p>
            <label for="<?php echo $this->get_field_id('direction'); ?>"><b><?php echo __('Direction:','subscribe_order_elitbuzz'); ?></b></label><br/>
            <input <?php if($direction=='up'){?>checked="checked" <?php } ?> type="radio" name="<?php echo $this->get_field_name('direction');?>"  id="direction_up" value="up"> <?php echo __('Up','subscribe_order_elitbuzz'); ?>
            <input <?php if($direction=='down'){?>checked="checked" <?php } ?> type="radio" name="<?php echo $this->get_field_name('direction');?>"  id="direction_down" value="down"> <?php echo __('Down','subscribe_order_elitbuzz'); ?>
        </p>
        <script>
		
		
            function chnageParam(newstype){
                
                if(newstype.value=='classic'){
                    
                    jQuery("[id$=-delay]").show();      
                    jQuery("[id$=-scrollamount]").show();      

                    jQuery("[id$=modern_scroller_delay]").hide();      
                    jQuery("[id$=modern_speed]").hide();      



                }
                else{

                    jQuery("[id$=modern_scroller_delay]").show();      
                    jQuery("[id$=modern_speed]").show();      
                    jQuery("[id$=-delay]").hide();      
                    jQuery("[id$=-scrollamount]").hide();      


                } 
            }
        </script>

        <?php
        } // function form
    } // widget class

    function vnsp_remove_extra_p_tags($content){

        if(strpos($content, 'print_verticalScroll_func')!==false){
        
            
            $pattern = "/<!-- print_verticalScroll_func -->(.*)<!-- end print_verticalScroll_func -->/Uis"; 
            $content = preg_replace_callback($pattern, function($matches) {


               $altered = str_replace("<p>","",$matches[1]);
               $altered = str_replace("</p>","",$altered);
              
                $altered=str_replace("&#038;","&",$altered);
                $altered=str_replace("&#8221;",'"',$altered);
              

              return @str_replace($matches[1], $altered, $matches[0]);
            }, $content);

              
            
        }
        
        $content = str_replace("<p><!-- print_verticalScroll_func -->","<!-- print_verticalScroll_func -->",$content);
        $content = str_replace("<!-- end print_verticalScroll_func --></p>","<!-- end print_verticalScroll_func -->",$content);
        
        
        return $content;
  }

  add_filter('widget_text_content', 'vnsp_remove_extra_p_tags', 999);
  add_filter('the_content', 'vnsp_remove_extra_p_tags', 999);


  //Ajax order status
add_action( 'wp_ajax_ajax_order_status', 'ajax_order_status' );
add_action( 'wp_ajax_nopriv_ajax_order_status', 'ajax_order_status' );
function ajax_order_status(){
	global $wpdb;
	
	$status = $_POST['statu'];
	$id =  $_POST['id'];
	
	    $result =   $wpdb->update( $wpdb->prefix."subscribe_order", 
                                        array( 
                                                'status' => $status 
                                        ), 
                                        array( 'id' => $id  ), 
                                        array( 
                                                '%s',	
                                                '%s'	
                                        ), 
                                        array( '%d' ) 
                                );
								
								echo $result;
								die();
}
 

  
 

 