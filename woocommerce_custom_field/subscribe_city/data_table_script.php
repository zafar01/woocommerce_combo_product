<?php
 

function show_datatable (){
 
function add_datatables_javaScript (){
        wp_register_script( 'dataTables-js', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' , '', '', true );
        wp_register_script( 'customScriptDatatables', plugins_url( 'includes/js/customScriptDatatables.js', __FILE__, '', true ) );
        wp_register_style( 'dataTables-css', '', '', '', true );
 
        
}
add_action( 'load_datatables_javascript', 'add_datatables_javaScript' );
  
} # function show_datatable


function jquery_tabify() {
  wp_enqueue_script('jquery-tabifyjs','https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', '');
    
    wp_enqueue_script( 'dataTables-js' );
        wp_enqueue_script( 'customScriptDatatables' );
        wp_enqueue_style( 'dataTables-css' );
}
add_action( 'admin_enqueue_scripts', 'jquery_tabify' );

function your_function() { ?>
<script type="text/javascript">
     
    
jQuery(document).ready( function () {
    jQuery('#RGdataTable').DataTable();
} );
    
 

</script>

    <?php
}
add_action( 'admin_footer', 'your_function' );






function add_custom_css_file( $hook ) {
  wp_enqueue_style(
  'your_custom_css_file',
  'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css');
}
add_action('admin_enqueue_scripts', 'add_custom_css_file');