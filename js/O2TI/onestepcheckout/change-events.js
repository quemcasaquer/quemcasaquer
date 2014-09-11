jQuery(function() {

	jQuery('[id="shipping:same_as_billing"]').click(function() {
				jQuery('#shipping_show').hide();
				jQuery("#o2ti-osc-p2").removeClass('onestepcheckout-numbers onestepcheckout-numbers-3').addClass('onestepcheckout-numbers onestepcheckout-numbers-2');
				jQuery("#o2ti-osc-p3").removeClass('onestepcheckout-numbers onestepcheckout-numbers-4').addClass('onestepcheckout-numbers onestepcheckout-numbers-3');
				jQuery("#o2ti-osc-p4").removeClass('onestepcheckout-numbers onestepcheckout-numbers-5').addClass('onestepcheckout-numbers onestepcheckout-numbers-4');
				flag = 1;
				shipping.setSameAsBilling(true);
				jQuery('shipping:same_as_billing').checked = false;
				jQuery('#shipping_show').hide();
				jQuery("#ship_to_same_address").val(1);
	});
	jQuery("#ship_to_same_address").bind({
		click: function() {
			val_address = jQuery("#ship_to_same_address").val();
			shipaddselect = jQuery("#shipping-address-select");
			billaddselect = jQuery("#billing-address-select");
			if (val_address == 1) {
				jQuery("#o2ti-osc-p2").removeClass('onestepcheckout-numbers onestepcheckout-numbers-2').addClass('onestepcheckout-numbers onestepcheckout-numbers-3');
				jQuery("#o2ti-osc-p3").removeClass('onestepcheckout-numbers onestepcheckout-numbers-3').addClass('onestepcheckout-numbers onestepcheckout-numbers-4');
				jQuery("#o2ti-osc-p4").removeClass('onestepcheckout-numbers onestepcheckout-numbers-4').addClass('onestepcheckout-numbers onestepcheckout-numbers-5');
				flag = 0;
				jQuery("#shipping_show").show();
				jQuery("#ship_to_same_address").val(0);
			} else {
				jQuery('#shipping_show').hide();
				jQuery("#o2ti-osc-p2").removeClass('onestepcheckout-numbers onestepcheckout-numbers-3').addClass('onestepcheckout-numbers onestepcheckout-numbers-2');
				jQuery("#o2ti-osc-p3").removeClass('onestepcheckout-numbers onestepcheckout-numbers-4').addClass('onestepcheckout-numbers onestepcheckout-numbers-3');
				jQuery("#o2ti-osc-p4").removeClass('onestepcheckout-numbers onestepcheckout-numbers-5').addClass('onestepcheckout-numbers onestepcheckout-numbers-4');
				flag = 1;
				shipping.setSameAsBilling(true);
				jQuery('shipping:same_as_billing').checked = false;
				jQuery('#shipping_show').hide();
				jQuery("#ship_to_same_address").val(1);
				
			}
		}
	});
	jQuery('#register_new_account').val(1);
	jQuery('#subscribe_newsletter').bind({
		click: function() {
			var val = 0;
			if (jQuery(this).is(':checked')) {
				val = 1;
			}
			jQuery(this).val(val);
		}
	});
	jQuery.fn.clearForm = function() {
		jQuery(this).find(':input').each(function() {
			switch (this.type) {
			case 'password':
			case 'text':
			case 'textarea':
				jQuery(this).val('');
			break;
			case 'checkbox':
			case 'radio':
			}
		});
	};
	jQuery('#allow_gift_messages').bind({
		click: function() {
			if (jQuery(this).is(':checked')) {
				jQuery('#allow-gift-message-container').show();
				} else {
					jQuery('#allow-gift-message-container').hide();
				}
		}
	});
});