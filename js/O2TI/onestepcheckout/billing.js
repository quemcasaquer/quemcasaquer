jQuery(function() {
	

	jQuery('#billing\\:confirm_password').focusout(function() {
		var val_pass=jQuery('#billing\\:customer_password').val();
		var val_pass2=jQuery('#billing\\:confirm_password').val();
		if(val_pass != val_pass2){
			jQuery("#senha_invalida").html('');
			jQuery(".senha_invalida").removeClass('validation-advice');
			jQuery('#billing\\:confirm_password').after('<div class="validation-advice senha_invalida" id="senha_invalida">As Senhas n&atilde;o s&atilde;o iguais</div>');}
		else{
			jQuery("#senha_invalida").html('');
			jQuery(".senha_invalida").removeClass('validation-advice');
		}
	});
	jQuery('#billing\\:taxvat').focusout(function() {
		var cpf = jQuery('#billing\\:taxvat').val();
		var cpf = cpf.replace(/\./g, "");
		var cpf = cpf.replace(/\-/g, "");
		if(cpf>0)
		TestaCPF(cpf);
	});

	jQuery('#billing\\:email').focusout(function() {
		jQuery('#advice-validate-email-billing\\:email').html("");
		jQuery('.email_invalido').hide();		
		val=jQuery('#billing\\:email').val();
		if(val!=""){
		var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
		if (testEmail.test(val)){
		jQuery('#advice-validate-email-billing\\:email').css({display:"none"});
		updateEmailmsg(val);
		}
		else{
		jQuery('#advice-validate-email-billing\\:email').html("");
		jQuery('#billing\\:email').after('<div class="validation-advice email_invalido" id="advice-validate-email-billing:email">Informe um endere&ccedil;o de email v&aacute;lido. Por exemplo seunome@gmail.com.</div>');
		}
	}
	});
	if (region_load()) {
		var regionship_before = jQuery('[id="billing:region"]').val();
		jQuery('[id="billing:region"]').bind({
			blur: function() {
				val = jQuery(this).val();
				if (val != "" && regionship_before != val) {
					updateShippingType();
				}
				regionship_before = val;
			}
		});
	}
	var counter = 0;
	jQuery('.form-list').find('.field').each(function(){
		if(counter%2==0){
			jQuery(this).addClass('even');
			if(jQuery(this).next().is('.wide')){
				counter++;
			}
		}else{
			jQuery(this).addClass('odd');
		}
		counter++;
	})

});

function valide_senha(val) {	
		var val_pass_count = jQuery('#billing\\:customer_password').val().length;
		var val_pass2_count =jQuery('#billing\\:confirm_password').val().length +1;
		if(val_pass_count == val_pass2_count){
			var val_pass=jQuery('#billing\\:customer_password').val();
			var val_pass2=jQuery('#billing\\:confirm_password').val();
			if(val_pass != val_pass2){
				jQuery("#senha_invalida").html('');
				jQuery(".senha_invalida").removeClass('validation-advice');
			}
		}
}

function TestaCPF(strCPF) {
	jQuery("#advice-validar_cpf").html('');
	jQuery(".advice-validar_cpf").removeClass('validation-advice');
    var Soma;
    var Resto;
    Soma = 0;
    if (strCPF == "00000000000" || strCPF == "11111111111" || strCPF == "22222222222" || strCPF == "33333333333" || strCPF == "44444444444" || strCPF == "55555555555" || strCPF == "66666666666"  || strCPF == "77777777777" ||  strCPF == "88888888888" ||  strCPF == "99999999999"){
			jQuery("#advice-validar_cpf").html('');
			jQuery(".advice-validar_cpf").removeClass('validation-advice');
			jQuery('#billing\\:taxvat').after('<div class="validation-advice advice-validar_cpf" id="advice-validar_cpf" style="display: block;">CPF inv&aacute;lido, o seu cpf ser&aacute; mantido em sig&iacute;lo, por&eacute;m &eacute; necess&aacute;rio para a emiss&atilde;o da Nota Fiscal</div>');
	}
    for (i=1; i<=9; i++){
		Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i); 
	}
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11)){
		Resto = 0;
	}
    if (Resto != parseInt(strCPF.substring(9, 10)) ){
    	jQuery("#advice-validar_cpf").html('');
			jQuery(".advice-validar_cpf").removeClass('validation-advice');
			jQuery('#billing\\:taxvat').after('<div class="validation-advice advice-validar_cpf" id="advice-validar_cpf" style="display: block;">CPF inv&aacute;lido, o seu cpf ser&aacute; mantido em sig&iacute;lo, por&eacute;m &eacute; necess&aacute;rio para a emiss&atilde;o da Nota Fiscal</div>');
    }
	Soma = 0;
    for (i = 1; i <= 10; i++){
       	Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    }
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11)){
		Resto = 0;
	}
    if (Resto != parseInt(strCPF.substring(10, 11) ) ){
    	jQuery("#advice-validar_cpf").html('');
			jQuery(".advice-validar_cpf").removeClass('validation-advice');
			jQuery('#billing\\:taxvat').after('<div class="validation-advice advice-validar_cpf" id="advice-validar_cpf" style="display: block;">CPF inv&aacute;lido, o seu cpf ser&aacute; mantido em sig&iacute;lo, por&eacute;m &eacute; necess&aacute;rio para a emiss&atilde;o da Nota Fiscal</div>');
    }
    return true;
}