<?php

class O2TI_Moip_Model_Observer
{
    public function createorderadmin($event)
    {
	$standard = Mage::getSingleton('moip/standard');
	$api = Mage::getSingleton('moip/api');
	$conta = $api->setContaMoip($standard->getConfigData('conta_moip'));
	$ambiente = $standard->getConfigData('ambiente');
	$api->setAmbiente($standard->getConfigData('ambiente'));
	$tem = $event->getOrderCreateModel();
	$que = $tem->getQuote();
	$orderid = "";
	$orderid = $que->getentity_id();
	$lastOrderId = $que->getOrder();
	$parametros = $event->getrequest();
	$key = $event->getparamentro();
	$formadepgto = $parametros ['forma_pagamento'];
	$pedido_form = "";
	$pedido_form = $key ['key'];
	if ($formadepgto == 'DebitoBancario'){
	$bandeira = $parametros [debito_instituicao];
	}
	if ($formadepgto == 'BoletoBancario'){
	$bandeira = "Bradesco";
	}
	if ($formadepgto == 'CartaoCredito'){
	$bandeira = $parametros [Bandeira];

	}
	$valor = $que->getgrand_total();
	$customer_id = $que->getcustomer_id();

	/*travado para dev */

	if ($bandeira != ""){
	$address = Mage::getModel('sales/order_address')->load($customer_id);
	$nomecli = $address->getFirstname() . ' ' . $address->getLastname();
	$email = $address->getEmail();
	$telefone = $address->getTelephone();
	$lougadoro = $address->getStreet(1);
	$numero = $address->getStreet(2);
	$complemento = $address->getStreet(2);
	$bairro = $address->getStreet(4);
	$cep = $address->getPostcode();
	$cidade = $address->getCity();
        $estado = strtoupper($address->getRegionCode());
        $alterapedido = rand(544, 15);
	$data['id_transacao'] = "13eliseiadm".$alterapedido;
        $pgtoArray = Array("conta_moip" => "elisei",
	        "apelido" => "localhost do bruno",
		"comissionamento" => "0",
		"parcelamento2" => "",
		"forma_pagamento" => $formadepgto);
	#preciso arrumar baseado nos dados do forme para formade pagamento e nos do admin para outros itens.
        $Arr = array(
            'id_carteira' => 'o2ti',
            'pagador_nome' => $nomecli,
            'id_transacao' => $data['id_transacao'],
	    'valor' => $valor,
            'pagador_email' => $email,
            'pagador_logradouro' => $lougadoro,
            'pagador_telefone' => $telefone,
	    'pagador_numero' => $numero,
	    'pagador_complemento' => $complemento,
            'pagador_bairro' => $bairro,
            'pagador_cep' => $cep,
            'pagador_cidade' => $cidade,
            'pagador_estado' => $estado,
        );
        $data = $Arr;
	$xml = $api->generateXML($data, $pgtoArray);
	$token = $api->getToken($xml);
	$token = $token['token'];
	$pedido = $data['id_transacao'];
		if ($xml != "" && $orderid != "" && $token != "" && $bandeira != "")
		{
		$connR = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql = "SELECT * FROM `moip` WHERE `realorder_id` = '".$orderid."'";
		$_venda = $connR->fetchAll($sql);
	 	foreach($_venda as $venda)
			{
				$update = $venda['realorder_id'];
			}
		if($update != "")
			{
			$hora = date('Y-m-d H:i:s');
			$conn = Mage::getSingleton('core/resource')->getConnection('core_write');
			$results = $conn->query("UPDATE `moip` SET `sale_id` = '".$pedido_form."' ,`xml_return` = '".$token."', `formapg` = '".$formadepgto."', `bandeira` = '".$bandeira."' WHERE `realorder_id` = '".$orderid."';");
			}
			else {
			$hora = date('Y-m-d H:i:s');
			$conn = Mage::getSingleton('core/resource')->getConnection('core_write');
			$results = $conn->query("INSERT INTO `moip` (
					`transaction_id` ,
					`realorder_id` ,
					`sale_id` ,
					`xml_sent` ,
					`xml_return` ,
					`status` ,
					`formapg` ,
					`bandeira` ,
					`digito` ,
					`vencimento` ,
					`datetime`
					)
					VALUES (
					NULL , '".$orderid."', '".$pedido_form."', '".$xml."', '".$token."', 'Sucesso', '".$formadepgto."', '".$bandeira."', '".$valor."', '', '".$hora."'
					);");
			}
		}
	}
	/*$orderid.$nomecli.$email.$lougadoro.$numero.$bairro.$cep.$cidade.$estado.$token*/


        return $this;
    }

   public function get_token($event)
    {
	$quote = $event->getOrder();
	$idmage = $quote->getquote_id();
	$valor = $quote->getgrand_total();
        $increment_id = $quote->getincrement_id();
        $connR = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = "SELECT * FROM `moip` WHERE `realorder_id` = '".$idmage."'";
	$_venda = $connR->fetchAll($sql);
 	foreach($_venda as $venda)
	{
		$sale_id = $venda['sale_id'];
	}
	if ($increment_id != $sale_id){
	$conn = Mage::getSingleton('core/resource')->getConnection('core_write');
	$results = $conn->query("UPDATE `moip` SET `sale_id` = '".$increment_id."' WHERE `realorder_id` = '".$idmage."';");
	}

 	return $this;
    }

   public function reorganiza($event)
   {
	$quote = $event->getOrder();
	$idmage = $quote->getquote_id();
	$valor = $quote->getgrand_total();
        $increment_id = $quote->getincrement_id();
	$customer_id = $quote->getcustomer_id();
	$address = Mage::getModel('sales/order_address')->load($customer_id);
	$nomecli = $address->getFirstname() . ' ' . $address->getLastname();
	$email = $address->getEmail();
	$orderInstance = $event->getOrder();
	$connR = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = "SELECT * FROM `moip` WHERE `realorder_id` = '".$idmage."'";
	$_venda = $connR->fetchAll($sql);
 	foreach($_venda as $venda)
	{
		$tokenpagamento = $venda['xml_return'];
	}
	$session = Mage::getSingleton('adminhtml/session');
	$orderHistoryComment = $tokenpagamento['0'];
	$this->getResponse()->setBody($session->getUpdateResult());
	$orderInstance->addStatusHistoryComment($orderHistoryComment, false);

   }

   public function generateInvoice($observer){
		
	}



}
