fechar = function(){
	jQuery('.moip-payment-method-content').slideUp();
}
function setCcType(ccType)
{
	jQuery('#moip_cc_type').val(ccType);
}

if(Validation) { 
	Validation.creditCartTypes = $H({
	    'VI': [new RegExp('^4[0-9]{12}([0-9]{3})?$'), new RegExp('^[0-9]{3}$'), true],
	    'MC': [new RegExp('^5[1-5][0-9]{14}$'), new RegExp('^[0-9]{3}$'), true],
	    'AE': [new RegExp('^3[47][0-9]{13}$'), new RegExp('^[0-9]{4}$'), true],
	    'DI': [false, new RegExp('^[0-9]{3}$'), true],
	    'OT': [false, new RegExp('^([0-9]{3}|[0-9]{4})?$'), false],
	    'EL': [false, new RegExp('^([0-9]{3})?$'), true],
	    'HI': [new RegExp('^(606282|3841)[0-9]'), new RegExp('^([0-9]{3})?$'), true]
	});
	
}
function countChar(val) {
	var cvv = val.value.length;
	if (cvv > 2) {
		jQuery('#formcli').hide();
		jQuery("#formcli").slideDown("slow");
		jQuery('#formcli').css({
			display: "block"
		});
		document.getElementById('credito_portador_nome').value = document.getElementById('billing:firstname').value + ' ' + document.getElementById('billing:lastname').value;
		document.getElementById('credito_portador_telefone').value = document.getElementById('billing:telephone').value;
		document.getElementById('credito_portador_cpf').value = document.getElementById('billing:taxvat').value;
		if (document.getElementById('billing:year').value) {
			document.getElementById('credito_portador_nascimento').value = document.getElementById('billing:day').value + '/' + document.getElementById('billing:month').value + '/' + document.getElementById('billing:year').value
		}
	}
};


