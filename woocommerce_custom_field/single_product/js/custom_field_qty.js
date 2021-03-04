

function count(str){
var element = jQuery('#qutyc input');
var p = element.map(function () {
    return this.value
}).get();
 var total = 0;
 
  jQuery.each( p, function( key, value ) {
   total =  (total)+  +  (value);
});
 
 var vali = jQuery("#validte").val();
 var subslimit = jQuery("#subscribelimit").val();
 var producttype = jQuery("#producttype").val();
 
 if(producttype == 'Subscription'){
	
var dates = jQuery('#mdp-demo').val();
 var len = dates.split(',').length;
 console.log(total +'>'+ vali);
 if((total == vali) && (len == subslimit) ){
	 jQuery(".single_add_to_cart_button").prop("disabled",false);
	 jQuery("#verror").html('');
 }else{
	 jQuery(".single_add_to_cart_button").prop("disabled",true);
	 jQuery("#verror").html('<p style="color:red">Limitation is only ' + vali + "KG and Select the "+ subslimit +" Delivery date  </p>");
 }
 
 }else{
	if(total == vali   ){
	 jQuery(".single_add_to_cart_button").prop("disabled",false);
	 jQuery("#verror").html('');
 }else{
	 jQuery(".single_add_to_cart_button").prop("disabled",true);
	 jQuery("#verror").html('<p style="color:red">Limitation is only ' + vali + "KG   </p>");
 } 
	 
	 
 }
 
}


/********* for Edit **/
function countupdate(str){
var element = jQuery('#qutyc input');
var p = element.map(function () {
    return this.value
}).get();
 var total = 0;
 
  jQuery.each( p, function( key, value ) {
   total =  (total)+  +  (value);
});
 
 var vali = jQuery("#validte").val();
 
 
 
 console.log(total +'>'+ vali);
 if(total == vali ){
	 jQuery(".single_add_to_cart_button").prop("disabled",false);
	 jQuery("#verror").html('');
 }else{
	 jQuery(".single_add_to_cart_button").prop("disabled",true);
	 jQuery("#verror").html('<p style="color:red">Limitation is only ' + vali + "KG  </p>");
 }
}