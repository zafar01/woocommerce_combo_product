jQuery(document).ready(function($) {
	
	$('#gift-demo').multiDatesPicker({ 
			 dateFormat: 'd M yy',
  // numberOfMonths: [1, 3],
    altField: '#altField',
    minDate: 1,
	maxPicks: 1,   
   // beforeShowDay: disableSpecificWeekDays,  
    onSelect: function(dateText) {
		 var dates = jQuery('#gift-demo').val();
		 
    jQuery("#gdeldate").val(dates);
	var len = dates.split(',').length;
	if(len == 1){
	jQuery(".single_add_to_cart_button").prop("disabled",false);
	}else{jQuery(".single_add_to_cart_button").prop("disabled",true);}
	}		
}); 


  
/*
function disableSpecificWeekDays(date) {
	  
  var theday = date.getDate() + '/' +
      (date.getMonth() + 1) + '/' +date.getFullYear();
  var day = date.getDay();
    if ($.inArray(day, obj) < 0) {
   return [false, ""]
  } else {
    return [true, ""]
  }
   
   
}*/

});