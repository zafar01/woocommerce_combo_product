<?php




function cities_function(){
 $action='gridview';
 if(isset($_GET['action']) and $_GET['action']!=''){
   $action=sanitize_text_field(trim($_GET['action']));
     }
  if(strtolower($action)==strtolower('gridview')){ 
  
  $messages=get_option('scrollnews_messages'); 
                $type='';
                $message='';
                if(isset($messages['type']) and $messages['type']!=""){

                    $type=sanitize_text_field($messages['type']);
                    $message=sanitize_text_field($messages['message']);

                }  

 
              
 ?>

	 
	 <h1><?php echo __("Subscribe Cities",'Subscribe-cities');?>&nbsp;&nbsp;<a class="button add-new-h2" href="admin.php?page=cities-slug&action=addedit"><?php echo __("Add New",'Subscribe-cities');?></a> </h1>
                    <br/>    
<?php  if(trim($type)=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";
			   }elseif(trim($type)=='succ'){ 
			   echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";
			   }
			   ?>
	<div style="background:#fff; padding:10px; width:75%;float:left; border:1px solid #ddd;">
	<table id="RGdataTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>S.NO.</th>
                <th>City Name</th>
                <th>Day</th>
                <th>Action</th>
                
                
            </tr>
        </thead>
        <tbody>
            <?php 
			global $wpdb;
			$city_list = $wpdb->prefix.'subscribe_cities';
            $result = $wpdb->get_results ( "SELECT * FROM $city_list");
            $delRecNonce=wp_create_nonce('delete_city');
			foreach($result as $k => $r){
				$id = $r->id;
				$editlink="admin.php?page=cities-slug&action=addedit&id=$id";
                $deletelink="admin.php?page=cities-slug&action=delete&id=$id&nonce=$delRecNonce";
			?>
            <tr>
                <td align="center">  <?= $k + 1;?> </td>
                <td align="center">  <?= $r->name;?></td>
                <td align="center"><?= $r->subsdays;?></td>
                 
                <td align="center">  <?php if($r->status == 1){?>
												 
												
												<a href='<?php echo $editlink; ?>' title="<?php echo __('Edit','vertical-news-scroller'); ?>" style="margin-right:15px"><?php echo __('Edit','vertical-news-scroller'); ?></a></strong>
												
												  
												 <strong><a href='<?php echo $deletelink; ?>' onclick="return confirmDelete();"  title="<?php echo __('Delete','vertical-news-scroller'); ?>"><?php echo __('Delete','vertical-news-scroller'); ?></a> </strong>  
												
												<?php } ?></td>
                
            </tr>
			<?php } ?>
			 
         
           
        </tbody>
        <tfoot>
            <tr>
                <th>S. No.</th>
                <th>City Name</th>
                <th>Day</th>
                 
                <th>Action</th>
                
            </tr>
        </tfoot>
    </table>
	</div>
	<?php
  }   
        else if(strtolower($action)==strtolower('addedit')){
			
			         
            if(isset($_POST['btnsave'])){

                 if(!check_admin_referer( 'action_news_add_edit','add_edit_nonce' )){
                
                        wp_die('Security check fail'); 
                   }
                   
                //edit save
                if(isset($_POST['cityid'])){

                    //add new

                    if ( ! current_user_can( 'vns_vertical_news_scroller_edit_news' ) ) {
                
                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','vertical-news-scroller');
                        update_option('scrollnews_messages', $scrollnews_messages);
                        $location="admin.php?page=cities-slug";
                        echo "<script type='text/javascript'> location.href='$location';</script>";
                        exit;


                    } 
                    global $wpdb;
                    $title=trim(htmlentities(sanitize_text_field($_POST['city_name']),ENT_QUOTES));
                    
					$days=trim(htmlentities(sanitize_text_field($_POST['daysname']),ENT_QUOTES));
                    
                    $cityid=intval(htmlentities(sanitize_text_field($_POST['cityid']),ENT_QUOTES));

                    $location='admin.php?page=cities-slug';
				 	$_POST['cityid'];
 
                    try{
                        
                        $wpdb->update( 
                                        $wpdb->prefix."subscribe_cities", 
                                        array( 
                                                'name' => $title,
												'subsdays' => $days
												
                                        ), 
                                        array( 'id' => $cityid, 'status' => 1 ), 
                                        array( 
                                                '%s',	
                                                '%s'	
                                        ), 
                                        array( '%d' ) 
                                );
                        
                 
                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='succ';
                        $scrollnews_messages['message']='City updated successfully.';
                        update_option('scrollnews_messages', $scrollnews_messages);


                    }
                    catch(Exception $e){

                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']='Error while updating City.';
                        update_option('scrollnews_messages', $scrollnews_messages);
                    }  

                    echo "<script> location.href='$location';</script>";
                }
                else{

                    //add new

                    if ( ! current_user_can( 'vns_vertical_news_scroller_add_news' ) ) {
                
                        
                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','vertical-news-scroller');
                        update_option('scrollnews_messages', $scrollnews_messages);
                         $location="admin.php?page=cities-slug";
						 
                        echo "<script type='text/javascript'> location.href='$location';</script>";
                        exit;
                        
                    } 
                    
                    $title=trim(htmlentities(sanitize_text_field($_POST['city_name']),ENT_QUOTES));
                    $daysname=trim(htmlentities(sanitize_text_field($_POST['daysname']),ENT_QUOTES));
                    
                    
                    $createdOn=current_time('mysql');

                        
                    $location='admin.php?page=cities-slug';
				   
                    try{
                        global $wpdb;
						 $query="SELECT * FROM ".$wpdb->prefix."subscribe_cities WHERE name='$title' and subsdays = '$daysname'";  
                                    $myrow  = $wpdb->get_row($query);
 if($title =="" || $daysname == ""){
	 $locations='admin.php?page=cities-slug&action=addedit&try=1';
									echo "<script> location.href='$locations';</script>"; 
 }
 
                                    if(is_object($myrow)){ $locations='admin.php?page=cities-slug&action=addedit&try=1';
									echo "<script> location.href='$locations';</script>";    
                                }else{
                        
                         $wpdb->insert( 
                                $wpdb->prefix."subscribe_cities", 
                                array( 
                                        'name' => $title, 
                                        'subsdays' => $daysname, 
                                        'createdon' => $createdOn,
                                        'status'	=> 1									
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
                        $scrollnews_messages['message']=__("city added successfully.",'vertical-news-scroller');
                        update_option('scrollnews_messages', $scrollnews_messages);


                    }
                    catch(Exception $e){

                        $scrollnews_messages=array();
                        $scrollnews_messages['type']='err';
                        $scrollnews_messages['message']=__("Error while adding news.",'vertical-news-scroller');
                        update_option('scrollnews_messages', $scrollnews_messages);
                    }  

                    echo "<script> location.href='$location';</script>";          

                } 

            }else{
			
			
			  if(isset($_GET['id']) and intval($_GET['id'])>0)
                                { 

                                     if ( ! current_user_can( 'vns_vertical_news_scroller_edit_news' ) ) {
                
                                        $scrollnews_messages=array();
                                        $scrollnews_messages['type']='err';
                                        $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','vertical-news-scroller');
                                        update_option('scrollnews_messages', $scrollnews_messages);
                                        $location="admin.php?page=cities-slug";
                                        echo "<script type='text/javascript'> location.href='$location';</script>";
                                         

                                    } 
                                    global $wpdb;
                                    $id= intval(htmlentities(sanitize_text_field($_GET['id']),ENT_QUOTES));
                                    $query="SELECT * FROM ".$wpdb->prefix."subscribe_cities WHERE id=$id";
                                    $myrow  = $wpdb->get_row($query);
									 

                                    if(is_object($myrow)){

                                        $title=stripslashes_deep($myrow->name);
										$subsdays=stripslashes_deep($myrow->subsdays);
                                         

                                    }   

                                ?>

                                <h1><?php echo __("Update News",'vertical-news-scroller'); ?></h1>
								<?php }else{?> 
								 <h1><?php echo __("Add Cities",'Subscribe-cities'); ?> </h1>
								 <?php } 
								
				$messages=get_option('scrollnews_messages'); 
                $type='';
                $message='';
                if(isset($messages['type']) and $messages['type']!=""){

                    $type=sanitize_text_field($messages['type']);
                    $message=sanitize_text_field($messages['message']);

                }  


          				
			?>
			
			<div id="poststuff">
                                <div id="post-body" class="metabox-holder columns-2">
                                    <div id="post-body-content">
                                        <form method="post" action="" id="addnews" name="addnews">

                                            <div class="stuffbox" id="namediv" style="width:100%; padding:20px">
                                                <h3><label for="link_name"><?php echo __("City Name",'Subscribe-cities'); ?></label></h3>
                                                <div class="inside">
                                                    <input required type="text" id="city_name"  class="required" style="width:30%"  size="30" name="city_name" value="<?php echo $title;?>">
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                    <div style="clear:both"></div>
                                                   <?php if($_GET['try']==1){ ?>
												   <p style="color:red"><?php echo __("Sorry! coupon is duplicate"); ?></p>
												   <?php }?>
                                                </div>
                                             
                                                <h3><label for="link_name"><?php echo __("Select Day",'Subscribe-cities'); ?></label></h3>
                                                <div class="inside">
                                                    <select required class="required" name="daysname" style="width:30%">
													<option value="">Select Day</option>
													<option value="Sunday" <?php if($subsdays == 'sunday'){echo 'selected';}?>>Sunday</option>
													<option value="Monday" <?php if($subsdays == 'Monday'){echo 'selected';}?>>Monday</option>
													<option value="Tuesday" <?php if($subsdays == 'Tuesday'){echo 'selected';}?>>Tuesday</option>
													<option value="Wednesday" <?php if($subsdays == 'Wednesday'){echo 'selected';}?>>Wednesday</option>
													<option value="Thursday"<?php if($subsdays == 'Thursday'){echo 'selected';}?>>Thursday</option>
													<option value="Friday" <?php if($subsdays == 'Friday'){echo 'selected';}?>>Friday</option>
													<option value="Saturday" <?php if($subsdays == 'Saturday'){echo 'selected';}?>>Saturday</option>
													
													</select> 
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                    <div style="clear:both"></div>
                                                   <?php if($_GET['try']==1){ ?>
												   <p style="color:red"><?php echo __("Sorry! coupon is duplicate"); ?></p>
												   <?php }?>
                                                </div>
                                            </div>
											
											
											
											
                                             
                                            <?php if(isset($_GET['id']) and intval(sanitize_text_field($_GET['id']))>0){ ?> 
                                                <input type="hidden" name="cityid" id="cityid" value="<?php echo intval(sanitize_text_field($_GET['id']));?>">
                                                <?php
                                                } 
                                            ?>
                                                
                                            <?php wp_nonce_field('action_news_add_edit','add_edit_nonce'); ?>    
                                            <input type="submit" name="btnsave" id="btnsave" value="<?php echo __('Save Changes','Subscribe-cities'); ?>" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancle" id="cancle" value="<?php echo __('Cancel','Subscribe-cities'); ?>" class="button-primary" onclick="location.href='admin.php?page=cities-slug'">

                                        </form> 
                                        <script>
                                            jQuery(document).ready(function() {  
                                                    jQuery("#addnews").validate({
                                                            errorClass: "news_error",
                                                            errorPlacement: function(error, element) {
                                                                error.appendTo( element.next().next().next());
                                                            }

                                                    })
                                            });

                                        </script> 

                                    </div>
                                </div>
                            </div> 
			<?php
			}
		}else if(strtolower($action)==strtolower('delete')){

             $retrieved_nonce = '';
            
            if(isset($_GET['nonce']) and $_GET['nonce']!=''){
              
                $retrieved_nonce=sanitize_text_field($_GET['nonce']);
                
            }
            if (!wp_verify_nonce($retrieved_nonce, 'delete_city' ) ){
        
                
                wp_die('Security check fail'); 
            }
              
            if ( ! current_user_can( 'vns_subscribe_city_delete_city' ) ) {
 
                $scrollnews_messages=array();
                $scrollnews_messages['type']='err';
                $scrollnews_messages['message']=__('Access Denied. Please contact your administrator','subscribe_order_elitbuzz');
                update_option('scrollnews_messages', $scrollnews_messages);
                $location="admin.php?page=cities-slug";
                echo "<script type='text/javascript'> location.href='$location';</script>";
                exit;
            } 
         
            $location='admin.php?page=cities-slug';
            $deleteId=intval(htmlentities(strip_tags($_GET['id']),ENT_QUOTES));

            try{
                global $wpdb;
                    $wpdb->query( 
                               $wpdb->prepare( 
                                       "
                                       DELETE FROM ".$wpdb->prefix."subscribe_cities
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
}




/******* User roll access **/
add_action( 'upgrader_process_complete',  'vns_vertical_news_upgrader_process_complete', 10, 4 );
  function vns_vertical_news_upgrader_process_complete(){        
        vns_vertical_news_scroller_add_access_capabilities();
    }
function vns_vertical_news_scroller_add_access_capabilities() {
     
    // Capabilities for all roles.
    $roles = array( 'administrator' );
    foreach ( $roles as $role ) {
        
            $role = get_role( $role );
            if ( empty( $role ) ) {
                    continue;
            }
         
            
          
            
            if(!$role->has_cap( 'vns_vertical_news_scroller_view_news' ) ){
            
                    $role->add_cap( 'vns_vertical_news_scroller_view_news' );
            }
            
            if(!$role->has_cap( 'vns_vertical_news_scroller_add_news' ) ){
            
                    $role->add_cap( 'vns_vertical_news_scroller_add_news' );
            }
            
            if(!$role->has_cap( 'vns_vertical_news_scroller_edit_news' ) ){
            
                    $role->add_cap( 'vns_vertical_news_scroller_edit_news' );
            }
            
            if(!$role->has_cap( 'vns_subscribe_city_delete_city' ) ){
            
                    $role->add_cap( 'vns_subscribe_city_delete_city' );
            }
            
            
         
    }
    
    $user = wp_get_current_user();
    $user->get_role_caps();
    
}


function map_vns_vertical_news_scroller_meta_caps( array $caps, $cap, $user_id, array $args  ) {
        
        
        if ( ! in_array( $cap, array( 
                                      'vns_vertical_news_scroller_view_news',
                                      'vns_vertical_news_scroller_add_news',
                                      'vns_vertical_news_scroller_edit_news',
                                      'vns_subscribe_city_delete_city'
                                    ), true ) ) {
            
			return $caps;
         }

       

   
        $caps = array();

        switch ( $cap ) {
              
              
                case 'vns_vertical_news_scroller_view_news':
                        $caps[] = 'vns_vertical_news_scroller_view_news';
                        break;
              
                case 'vns_vertical_news_scroller_add_news':
                        $caps[] = 'vns_vertical_news_scroller_add_news';
                        break;
              
                case 'vns_vertical_news_scroller_edit_news':
                        $caps[] = 'vns_vertical_news_scroller_edit_news';
                        break;
              
                case 'vns_subscribe_city_delete_city':
                        $caps[] = 'vns_subscribe_city_delete_city';
                        break;
              
                default:
                        
                        $caps[] = 'do_not_allow';
                        break;
        }

      
     return apply_filters( 'vns_vertical_news_scroller_map_meta_caps', $caps, $cap, $user_id, $args );
}

add_action('plugins_loaded', 'vns_load_lang_for_vertical_news_scroller');
function vns_load_lang_for_vertical_news_scroller() {

            
            add_filter( 'map_meta_cap',  'map_vns_vertical_news_scroller_meta_caps', 10, 4 );
            add_filter( 'user_has_cap', 'vns_vertical_news_admin_cap_list' , 10, 4 );
    }
	
	
	function vns_vertical_news_admin_cap_list($allcaps, $caps, $args, $user){
        
        
        if ( ! in_array( 'administrator', $user->roles ) ) {
            
            return $allcaps;
        }
        else{
            
            if(!isset($allcaps['vns_vertical_news_scroller_view_news'])){
                
                $allcaps['vns_vertical_news_scroller_view_news']=true;
            }
            
            if(!isset($allcaps['vns_vertical_news_scroller_add_news'])){
                
                $allcaps['vns_vertical_news_scroller_add_news']=true;
            }
            
            if(!isset($allcaps['vns_vertical_news_scroller_edit_news'])){
                
                $allcaps['vns_vertical_news_scroller_edit_news']=true;
            }
            
            if(!isset($allcaps['vns_subscribe_city_delete_city'])){
                
                $allcaps['vns_subscribe_city_delete_city']=true;
            }
            
        }
        
        return $allcaps;
    }