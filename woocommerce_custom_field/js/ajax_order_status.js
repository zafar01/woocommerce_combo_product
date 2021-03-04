
 function pendingdis(statu, id){
	 jQuery("#orrderload").show();
	 
		var data = {
			'action': 'ajax_order_status' ,
    id : id,
	statu:statu
		};
 
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	 	jQuery.post(aplugin_ajax_object.ajax_url , data, function(response) {
			 console.log('Got this from the server: ' + response );
			 jQuery("#subscribe_oreders").html("<p>Order id " + response + "status successfully updated </p> ");
			 if(response == 1){jQuery("#succesd").html("Order Successfully Updated.");jQuery("#succesd").show();}
			  jQuery("#orrderload").hide();
		}); 
		 
		
		
		  } ;
		  
		  jQuery("#no-more-tables").show();
	