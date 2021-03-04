(function($) { 
    jQuery.fn.multiselect = function() {
    $(this).each(function() {
        var checkboxes = $(this).find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = $(this);
            // Highlight pre-selected checkboxes
            if (checkbox.prop("checked"))
                checkbox.parent().addClass("multiselect-on");
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.prop("checked"))
                    checkbox.parent().addClass("multiselect-on");
                else
                    checkbox.parent().removeClass("multiselect-on");
            });
        });
    });
};
})( jQuery );
 
(function($) { 
    $(".multiselect").multiselect();
})( jQuery );


(function($){ 
$("#custom_subscribe").change(function() { 
	$vac = $("#custom_subscribe").val();
if($vac == 'Subscription'){
$("#_number_of_subscription").prop('required',true);
$("#custom_product_limit").prop('required',true);
$("#custom_number_of_subscription").show();	

}else if($vac == 'Combo' || $vac == 'Gift'){
	$("#custom_product_limit").prop('required',true);
}else{
	$("#custom_product_limit").prop('required',false);
	$("#_number_of_subscription").prop('required',false);
	$("#_number_of_subscription").val('');
	$("#custom_number_of_subscription").hide();
} 
 	
});
})( jQuery );