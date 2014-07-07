jQuery(function() {		
	if (region_load()) {
		var regionship_before = jQuery('[id="shipping:region"]').val();
		jQuery('[id="shipping:region"]').bind({
			blur: function() {
				val = jQuery(this).val();
				if (val != "" && regionship_before != val) {
					updateShippingType();
				}
				regionship_before = val;
			}
		});
	}
});