<?php
function mysite_admin_menu(){
add_menu_page('Subscribe Cities', 'Subscribe Cities', 'activate_plugins', 'cities-slug', 'cities_function');
$hook_suffix_v_n= add_submenu_page( 'cities-slug', 'Subscribe Order', 'Subscribe Order', 'activate_plugins', 'subscribe-order-slug', 'subscribe_order_function');

add_action( 'load-' . $hook_suffix_v_n , 'vertical_news_scroller_plugin_admin_init' );
}
 
add_action('admin_menu', 'mysite_admin_menu');

?>