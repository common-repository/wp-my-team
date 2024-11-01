

function myteamshortcodegenerate() {
	
	
	
	
	var order = document.getElementById('orderby').value;
	var category = document.getElementById('category').value;
	var url = document.getElementById('singleurl').value;
	var imgstyle = document.getElementById('imgstyle').value;
	var columns = document.getElementById('columns').value;
	var imgeffect = document.getElementById('imgeffect').value;
	var textalign = document.getElementById('textalign').value;
	var composition = document.getElementById('composition').value;
	var layout = document.getElementById('layout').value;
	var limit = document.getElementById('limit').value;
	var idsfilter = document.getElementById('idsfilter').value;
	
	var tablestyling = document.getElementById('table-styling').value;
	var gridstyling = document.getElementById('grid-styling').value;
	var hoverstyling = document.getElementById('hover-styling').value;
	var pagerstyling = document.getElementById('pager-styling').value;
	var pagercomposition = document.getElementById('pagercomposition').value;
	var pagerimgcomposition = document.getElementById('pagerimgcomposition').value;
	var img = document.getElementById('img').value;
	var filtergrid = document.getElementById('filtergrid').value;
	var filterhover = document.getElementById('filterhover').value;
	
	if(layout=="grid") {
		gridop();
	}
	if(layout=="pager" ) {
		pagerop();
	}
	if(layout=="table" ) {
		tableop();
	}
	if(layout=="hover" ) {
		hoverop();
	}
	
	//display
	var display = "";
	if(document.getElementById('photo').checked) { display=display+'photo,'; imgopshow();} else { imgophide(); }
	if(document.getElementById('website').checked) { display=display+'website,';}
	if(document.getElementById('position').checked) { display=display+'position,';}
	if(document.getElementById('social').checked) { display=display+'social,';}
	if(document.getElementById('location').checked) { display=display+'location,';}
	if(document.getElementById('email').checked) { display=display+'email,';}
	if(document.getElementById('telephone').checked) { display=display+'telephone,';}
	if(document.getElementById('smallicons').checked) { display=display+'smallicons,';}
	if(document.getElementById('name').checked) { display=display+'name,';}
	
	if(display.length >= 1) { display = display.slice(0, - 1);}
	
	var shortcode = document.getElementById('shortcode');
	var shortcode2 = document.getElementById('shortcode2');
	var php = document.getElementById('phpcode');
	var preview = document.getElementById('preview');
	
	var shortcodeinner = "[my-team ";
	
	if(order!="none") {
		shortcodeinner += " orderby='"+order+"'";
		}
	if(limit!="0" && limit !="") {
		shortcodeinner += " limit='"+limit+"'";
	}
	if(idsfilter!="0"){
		shortcodeinner += " idsfilter='"+idsfilter+"'";	
	}
	if(category!="0") {
	 	shortcodeinner += " category='"+category+"'";	
	}
	if(url!="inactive") {
	 	shortcodeinner += " url='"+url+"'";	
	}
	
	if(layout!="") {
	 	shortcodeinner += " layout='"+layout+"'";	
	}
	
	var style ="";
	
	
	//Image and Text Styles
	if(document.getElementById('photo').checked) {
		if(imgstyle!="") {
			style += imgstyle;	
		}
		
		if(imgeffect!="") {
			style += ","+imgeffect;	
		}
	}
	
	if(textalign!="") {
	 	style += ","+textalign;	
	}
	
	
	//layout dependent styles
	
	if(layout=="grid") {
	
		if(composition!="") {
			style += ","+composition;	
		}
		
		if(columns!="") {
			style += ","+columns;	
		}
		
		if(gridstyling!="" && gridstyling!="default" ) {
			style += ","+gridstyling;
		}
		
		if(filtergrid!="" && filtergrid!="inactive") {
			display += ","+filtergrid;
		}
		
	}
	
	if(layout=="hover") {
	
		if(columns!="") {
			style += ","+columns;	
		}
		if(hoverstyling!="" && hoverstyling!="default" ) {
			style += ","+hoverstyling;
		}
		
		if(filterhover!="" && filterhover!="inactive" ) {
			display += ","+filterhover;
		}
		
	}
	
	if(layout=="table") {
		if(tablestyling!="" && tablestyling!="default" ) {
			style += ","+tablestyling;
		}
	}
	
	if(layout=="pager") {
		if(pagerstyling!="" && pagerstyling!="default" ) {
			style += ","+pagerstyling;
		}
		
		if(pagercomposition!="") {
			style += ","+pagercomposition;	
		}
		if(pagerimgcomposition!="") {
			style += ","+pagerimgcomposition;	
		}
		
	}
	
	//Final composition for style
	if(style!="") {
	 	shortcodeinner += " style='"+style+"'";	
	}
	 
	 if(display!="") {
	 	shortcodeinner += " display='"+display+"'";	
	}
	
	if (img!="" && layout!="table") {
		shortcodeinner += " img='"+img+"'";
	}
	 
	 shortcodeinner += "]";
		
	shortcode.innerHTML = shortcodeinner;
	shortcode2.innerHTML = shortcodeinner;
	
	php.innerHTML = "&lt;?php echo create_myteam('"+order+"','"+limit+"','"+idsfilter+"','"+category+"','"+url+"','"+layout+"','"+style+"','"+display+"','"+img+"'); ?&gt; ";
	

var data = {
		action: 'myteam',
		porder: order,
		plimit: limit,
		pidsfilter: idsfilter,
		pcategory:category,
		purl:url,
		playout:layout,
		pstyle:style,
		pdisplay:display,
		pimg:img
	};
	
	
jQuery.post(ajax_object.ajax_url, data, function(response) {
		preview.innerHTML=response;
		
		checkscripts();
		
	});
	
	
	
	
	
}

function  gridop() {
		var e = document.getElementById('pagerdiv');
        e.style.display = 'none';
		var e = document.getElementById('tablediv');
        e.style.display = 'none';
		var e = document.getElementById('hoverdiv');
        e.style.display = 'none';
		var a = document.getElementById('griddiv');
        a.style.display = 'block';
		var e = document.getElementById('imgsize');
        e.style.display = 'block';
		var e = document.getElementById('columnsdiv');
        e.style.display = 'block';
	}

function  pagerop() {
		var e = document.getElementById('pagerdiv');
        e.style.display = 'block';
		var a = document.getElementById('griddiv');
        a.style.display = 'none';
		var e = document.getElementById('tablediv');
        e.style.display = 'none';
		var e = document.getElementById('hoverdiv');
        e.style.display = 'none';
		var e = document.getElementById('imgsize');
        e.style.display = 'block';
		var e = document.getElementById('columnsdiv');
        e.style.display = 'none';
	}

function  tableop() {
		var a = document.getElementById('tablediv');
        a.style.display = 'block';
		var b = document.getElementById('hoverdiv');
        b.style.display = 'none';
		var c = document.getElementById('pagerdiv');
        c.style.display = 'none';
		var d = document.getElementById('griddiv');
        d.style.display = 'none';
		var e = document.getElementById('imgsize');
        e.style.display = 'none';
		var e = document.getElementById('columnsdiv');
        e.style.display = 'none';
		
		
	}

function  hoverop() {
		var e = document.getElementById('tablediv');
        e.style.display = 'none';
		var e = document.getElementById('hoverdiv');
        e.style.display = 'block';
		var e = document.getElementById('pagerdiv');
        e.style.display = 'none';
		var a = document.getElementById('griddiv');
        a.style.display = 'none';
		var e = document.getElementById('imgsize');
        e.style.display = 'block';
		var e = document.getElementById('columnsdiv');
        e.style.display = 'block';
	}

function imgophide() {
		var e = document.getElementById('imgdiv');
        e.style.display = 'none';
}

function imgopshow() {
		var e = document.getElementById('imgdiv');
        e.style.display = 'block';
}

function checkscripts() {
	
	var layout = document.getElementById('layout').value;
	var filtergrid = document.getElementById('filtergrid').value;
	var filterhover = document.getElementById('filterhover').value;
	
	if(layout=="pager") {
		
		jQuery('.myteam-bxslider-0').bxSlider({
			  pagerCustom: '#myteam-bx-pager-0',
			  controls:false,
			  mode:'fade'
		});	
		
	}
	
	if (layout=="grid") {
	
		if(filtergrid == "filter") { 
		
			jQuery("#mt-filter-nav > li").off('click');
	
			jQuery('#mt-all').addClass('mt-current-li');
			jQuery("#mt-filter-nav > li").click(function(){
				ts_show(this.id);
			});
		}
		if(filtergrid == "enhance-filter") { 
	
			jQuery("#mt-enhance-filter-nav > li").off('click');
			
			jQuery('#mt-all').addClass('mt-current-li');
			jQuery("#mt-enhance-filter-nav > li").click(function(){
				ts_show_enhance(this.id);
			});
		}	
	}
	
	if (layout=="hover") {
		//Filter Code
		if(filterhover == "filter"  ) {
			
			jQuery("#mt-filter-nav > li").off('click');
			
			jQuery('#mt-all').addClass('mt-current-li');
			jQuery("#mt-filter-nav > li").click(function(){
				ts_show(this.id);
			});
		}
	
		if(filterhover == "enhance-filter"  ) {
			
					
			jQuery("#mt-enhance-filter-nav > li").off('click');
			
			jQuery('#mt-all').addClass('mt-current-li');
			jQuery("#mt-enhance-filter-nav > li").click(function(){
				ts_show_enhance(this.id);
			});
		}	
	}
}

//myteamshortcodegenerate();
myteampreset();


//Still in development
function loadshortcode() {
	var shortcode = document.getElementById('loadshortcode').value;
	var result = document.getElementById('result');
	
   var params = shortcode.match(/\b\w+='[^']+'/g);
   for (var i = 0; i < params.length; i++)
      result.innerHTML += params[i]+"<br>";
	  
	 }

function myteampreset() {
	
	var preset = document.getElementById('preset').value;
	
	var imgstyle = document.getElementById('imgstyle');
	var columns = document.getElementById('columns');
	var imgeffect = document.getElementById('imgeffect');
	var textalign = document.getElementById('textalign');
	var composition = document.getElementById('composition');
	var layout = document.getElementById('layout');
	var tablestyling = document.getElementById('table-styling');
	var gridstyling = document.getElementById('grid-styling');
	var hoverstyling = document.getElementById('hover-styling');
	var pagerstyling = document.getElementById('pager-styling');
	var pagercomposition = document.getElementById('pagercomposition');
	var pagerimgcomposition = document.getElementById('pagerimgcomposition');
	var filtergrid = document.getElementById('filtergrid');
	var filterhover = document.getElementById('filterhover');
	
	var img = document.getElementById('img').value;
	
	if(preset=='polaroid') {
		layout.value = "grid";
		imgstyle.value = "img-square";
		gridstyling.value = "retro-box-theme";
		composition.value = "img-above";
		imgeffect.value = "";
		textalign.value = "text-left";
		columns.value = "3-columns";
		filtergrid.value ="inactive";
	}
	
	if(preset=='white-polaroid') {
		layout.value = "grid";
		imgstyle.value = "img-square";
		gridstyling.value = "white-box-theme";
		composition.value = "img-above";
		imgeffect.value = "";
		textalign.value = "text-left";
		columns.value = "3-columns";
		filtergrid.value ="inactive";
	}
	
	if(preset=='gray-card-grid') {
		layout.value = "grid";
		imgstyle.value = "img-square";
		gridstyling.value = "card-theme";
		composition.value = "img-above";
		imgeffect.value = "";
		textalign.value = "text-left";
		columns.value = "3-columns";
		filtergrid.value ="inactive";
	}
	
	if(preset=='circle-grid') {
		layout.value = "grid";
		imgstyle.value = "img-circle";
		gridstyling.value = "default";
		composition.value = "img-above";
		imgeffect.value = "img-white-border";
		textalign.value = "text-center";
		columns.value = "3-columns";
		filtergrid.value ="inactive";
	}
	
	if(preset=='content-right-simple-grid') {
		layout.value = "grid";
		imgstyle.value = "img-square";
		gridstyling.value = "default";
		composition.value = "img-left";
		imgeffect.value = "img-white-border";
		textalign.value = "text-left";
		columns.value = "2-columns";
		filtergrid.value ="inactive";
	}
	
	if(preset=='content-below-simple-grid') {
		layout.value = "grid";
		imgstyle.value = "img-rounded";
		gridstyling.value = "default";
		composition.value = "img-above";
		imgeffect.value = "img-white-border";
		textalign.value = "text-left";
		columns.value = "3-columns";
		filtergrid.value ="inactive";
	}
	
	if(preset=='hover-circle-white-grid') {
		layout.value = "hover";
		imgstyle.value = "img-circle";
		hoverstyling.value = "white-hover";
		imgeffect.value = "";
		textalign.value = "text-center";
		columns.value = "3-columns";
		filterhover.value ="inactive";
	}
	
	if(preset=='hover-circle-grid') {
		layout.value = "hover";
		imgstyle.value = "img-circle";
		hoverstyling.value = "default";
		imgeffect.value = "img-white-border";
		textalign.value = "text-center";
		columns.value = "3-columns";
		filterhover.value ="inactive";
	}
	if(preset=='hover-square-grid') {
		layout.value = "hover";
		imgstyle.value = "img-square";
		hoverstyling.value = "default";
		imgeffect.value = "img-white-border";
		textalign.value = "text-left";
		columns.value = "4-columns";
		filterhover.value ="inactive";
	}
	if(preset=='simple-table') {
		layout.value = "table";
		imgstyle.value = "img-square";
		tablestyling.value = "odd-colored";
		imgeffect.value = "";
		textalign.value = "text-left";
		
	}
	
	if(preset=='simple-pager') {
		layout.value = "pager";
		imgstyle.value = "img-square";
		pagerstyling.value = "default";
		imgeffect.value = "img-white-border";
		textalign.value = "text-left";
		pagercomposition.value = "thumbs-left";
		pagerimgcomposition.value = "img-above";
		
	}
	
	if(preset=='circle-pager') {
		layout.value = "pager";
		imgstyle.value = "img-circle";
		pagerstyling.value = "default";
		imgeffect.value = "img-white-border";
		textalign.value = "text-center";
		pagercomposition.value = "thumbs-left";
		pagerimgcomposition.value = "img-above";
		
	}
	
	if(preset=='gallery-pager') {
		layout.value = "pager";
		imgstyle.value = "img-square";
		pagerstyling.value = "default";
		imgeffect.value = "img-white-border";
		textalign.value = "text-left";
		pagercomposition.value = "thumbs-below";
		pagerimgcomposition.value = "img-left";
		
	}
	
	
	myteamshortcodegenerate();
}