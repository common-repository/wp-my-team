
jQuery.noConflict();


jQuery('#mt-all').addClass('mt-current-li');
    jQuery("#mt-filter-nav > li").click(function(){
        ts_show(this.id);
    });
	


//FILTER CODE
function ts_show(category) {	 
	
	if (category == "mt-all") {
        jQuery('#mt-filter-nav > li').removeClass('mt-current-li');
        jQuery('#mt-all').addClass('mt-current-li');
        jQuery('.myteam-filter-active').show('slow');
		}
	
	else {
		jQuery('#mt-filter-nav > li').removeClass('mt-current-li');
   		jQuery('#' + category).addClass('mt-current-li');  
		jQuery('.' + category).show('slow');
		jQuery('.myteam-filter-active:not(.'+ category+')').hide('slow');
	}
	
}