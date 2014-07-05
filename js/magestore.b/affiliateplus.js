var MapAccount = Class.create();
MapAccount.prototype = {
    initialize: function(changeCustomerUrl){
        
        this.changeCustomerUrl = changeCustomerUrl;
       
    },
	
	changeCustomer : function(customerId)
	{
		var url = this.changeCustomerUrl;
		
		url += 'customer_id/' + customerId;

		new Ajax.Updater('map-customer-info',url,{method: 'get', onComplete: function(){updateAccountInfo();} ,onFailure: ""}); 	
		
	}
}

function updateAccountInfo()
{
	$('name').value = $('map_customer_name').value;
	$('email').value = $('map_customer_email').value;
	$('customer_id').value = $('map_customer_id').value;
}

function affiliateResetForm()
{
	location.href='';
}