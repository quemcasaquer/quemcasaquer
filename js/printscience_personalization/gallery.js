(function($) {
    $(window).load(function() {
    	$('.personalizationGallery').each(function(index, el) {
    		var imgHeightList = [];
    		
    		$(el).find('img').each(function(index, el) {	
    			$(el).css('display', 'block');
    			imgHeightList.push($(el).height());
    		});
    		
    		$(el).data('tmpHeight', Math.max.apply(null, imgHeightList));
    	});
    	
    	$('.personalizationGallery').cycle({ 
            fx:     'fade', 
            speed:   300, 
            timeout: 3000,
            pause:   1
    	});
    		
    	$('.personalizationGallery').each(function(index, el) {
    		//$(el).css('height', $(el).data('tmpHeight'));
    	});		
    })
    $(document).ready(function() {
		$("a.pgImg").fancybox();
	})    
})(jQuery)
