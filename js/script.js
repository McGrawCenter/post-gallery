jQuery(document).ready(function(){

  var category_array = [];
  
  

  function tileTemplate(postobj) {
    var html = "<a class='pu-post-gallery-tile three-cols fade-up' href='"+postobj.link+"' style='background-image:url("+postobj.thumbnail_large+");'><div class='pu-post-gallery-tile-title'>"+postobj.title.rendered+"</div></a>";
    return html;
  }


  function paginate(current, total, perpage) {
    var html = "<ul id='post-gallery-pagination'>";
    var pages = Math.ceil(total/perpage);
    if(pages > 1) {
      for(var x=1;x<=pages;x++) {
        if(x == current) { html += "<li><a href='#' class='paged current' rel='"+x+"'>"+x+"</a></li>"; }
        else { html += "<li><a href='#' class='paged' rel='"+x+"'>"+x+"</a></li>"; }
      }
    }
    html += "</ul>";
    return html;
  }
  
  
  jQuery(document).on('click','.paged', function(e){
  
      var page = jQuery(this).attr('rel');
      updateGallery(page);
      e.preventDefault();
  }); 
  



	function updateGallery(page = 1) {
	
	  var perpage = 21;
	  var offset = (page-1) * perpage;
	
	  var catstr = category_array.join(',');
	
	  var d = {'categories':catstr,'per_page':perpage,'offset':offset}
	
	  var json = vars.site_url+"/wp-json/wp/v2/posts";

	  jQuery.get(json, d, function(data, status, xhr){  
	  
	    var total = xhr.getResponseHeader('x-wp-total');
	    var pag = paginate(page,total,perpage);
	  
	    jQuery('#gallery').empty();
	    jQuery.each(data, function(i,v){
	
	      var html = tileTemplate(v);
	      jQuery('#gallery').append(html);
	    
	    });
	    
	    jQuery('#gallery').append(pag);
	  })

	}
	
	
	jQuery('.cat_filter').click(function(e){
	  if(jQuery(this).hasClass('selectedfilter')) { jQuery(this).removeClass('selectedfilter'); }
	  else { jQuery(this).addClass('selectedfilter');  }
	  
	  var rel = jQuery(this).attr('rel');
	  
	  category_array = [];
	  jQuery('.selectedfilter').each(function(i,v) {
	    category_array.push(v.rel);
	  });
	  updateGallery();
	  e.preventDefault();
	});
	
	
	
       updateGallery();

});

