
jQuery.noConflict();


	jQuery('#mt-all').addClass('mt-current-li');
    jQuery("#mt-enhance-filter-nav > li").click(function(){
        ts_show_enhance(this.id);
    });
	


//FILTER CODE
function ts_show_enhance(category) {	

	if (category == "mt-all") {
        jQuery('#mt-enhance-filter-nav > li').removeClass('mt-current-li');
        jQuery('#mt-all').addClass('mt-current-li');
        jQuery('.myteam-filter-active').addClass('mt-current').removeClass('mt-not-current');
		}
	
	else {
		jQuery('#mt-enhance-filter-nav > li').removeClass('mt-current-li');
   		jQuery('#' + category).addClass('mt-current-li');  
		jQuery('.' + category).addClass('mt-current').removeClass('mt-not-current'); 
		jQuery('.myteam-filter-active:not(.'+ category+')').addClass('mt-not-current').removeClass('mt-current');
	}
	
}