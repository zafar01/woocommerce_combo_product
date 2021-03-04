

/*****************************************************************
 (function($) { 
var dates = $('#mdp-demo').multiDatesPicker({ 
  // numberOfMonths: [1, 3],
    altField: '#altField',
    minDate: 2,
	maxPicks: 5,   
    beforeShowDay: disableSpecificWeekDays,  
     
});;

 

function disableSpecificWeekDays(date) {
  var theday = date.getDate() + '/' +
      (date.getMonth() + 1) + '/' +date.getFullYear();
  var day = date.getDay();
    return [day != 1 && day != 2 && day != 3 && day != 4 && day != 5 && day != 6];
}
})( jQuery );

**/