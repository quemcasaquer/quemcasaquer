Event.observe(window, 'load', function() {
	function jsColor(mainId, exceptions){
		if($$(mainId).length){
			var selection = 'input.input-text:not('+ exceptions +')';
			var selected_items = $$(mainId)[0].select(selection);
			selected_items.each(function(val){
				new jscolor.color(val);
			});
		}
	}
	jsColor('#meigee_blacknwhite_design_base');
	jsColor('#meigee_blacknwhite_design_catlabels');
	jsColor('#meigee_blacknwhite_design_menu', '#meigee_blacknwhite_design_menu_block_border_width');
	jsColor('#meigee_blacknwhite_design_headerslider');
	jsColor('#meigee_blacknwhite_design_buttons', '#meigee_blacknwhite_design_buttons_buttons_border_width, #meigee_blacknwhite_design_buttons_buttons_2_border_width');
	jsColor('#meigee_blacknwhite_design_social_links', '#meigee_blacknwhite_design_social_links_social_links_border_width');
	jsColor('#meigee_blacknwhite_design_footer', '#meigee_blacknwhite_design_footer_footer_top_title_border_width, #meigee_blacknwhite_design_footer_footer_medium_title_border_width');
	jsColor('#meigee_blacknwhite_design_products', '#meigee_blacknwhite_design_products_products_border_width, #meigee_blacknwhite_design_products_products_divider_width');
	jsColor('#meigee_blacknwhite_design_header');
});