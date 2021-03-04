jQuery(document).ready(function($) {
$("#dcity").change(function(){
	var city = $("#dcity").val();
	$("#days").show();
	$("#mdp-demo").hide();
	jQuery(".single_add_to_cart_button").prop("disabled",true);
	
	$('#mdp-demo').multiDatesPicker('resetDates', 'picked');
	
	  jQuery("#deldate").val('');
	  
	 jQuery("#days").html('<option value="1">... Loading</option>');
		var data = {
			'action': 'my_action',
			'city': city
		};
 
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	 	jQuery.post(wc_add_to_cart_params.ajax_url, data, function(response) {
			 
			  $("#days").hide();
			 $("#mdp-demo").show();
			
			var subslimit = $("#subscribelimit").val();
			var obj = JSON.parse(response);
			 $('#mdp-demo').multiDatesPicker({ 
			 dateFormat: 'd M yy',
  // numberOfMonths: [1, 3],
    altField: '#altField',
    minDate: 1,
	maxPicks: subslimit,   
    beforeShowDay: disableSpecificWeekDays,  
    onSelect: function(dateText) {
		 var dates = jQuery('#mdp-demo').val();
    jQuery("#deldate").val(dates);
	var len = dates.split(',').length;
	if(len == subslimit){
	jQuery(".single_add_to_cart_button").prop("disabled",false);
	}else{jQuery(".single_add_to_cart_button").prop("disabled",true);}
	}		
}); 


  

function disableSpecificWeekDays(date) {
	  
  var theday = date.getDate() + '/' +
      (date.getMonth() + 1) + '/' +date.getFullYear();
  var day = date.getDay();
    if ($.inArray(day, obj) < 0) {
   return [false, ""]
  } else {
    return [true, ""]
  }
   
   
}

 




		}); 
		
		  });
		 
		 
		 
		




	});
	
 