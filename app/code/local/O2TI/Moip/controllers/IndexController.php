<?php 
class O2TI_Moip_IndexController extends Mage_Core_Controller_Front_Action
{	
	protected function indexAction() {
		$this->loadLayout();
        $this->renderLayout();
        return true;
	}
	public function NovaformaAction() {
		if($this->getRequest()->getParams()){
			$model = Mage::getModel('moip/write');
			$api = Mage::getModel('moip/api');
			$post = $this->getRequest()->getPost();
        	$model->load($post['order_id'], 'realorder_id');
        	$model->getDate();
			$pgtoArray = array();
			$pgtoArray['forma_pagamento'] = $post['forma_de_pagamento'];
			$pgtoArray['credito_instituicao'] = $post['bandeira'];
	        $pgtoArray['credito_numero'] = $post['Numero'];
	        $pgtoArray['credito_expiracao_mes'] = $post['Expiracao_mes'];
	        $pgtoArray['credito_expiracao_ano'] = $post['Expiracao_ano'];
	        $pgtoArray['credito_codigo_seguranca'] = $post['CodigoSeguranca'];
	        $pgtoArray['credito_parcelamento'] = $post['parcelas'];
	        $pgtoArray['credito_portador_nome'] = $post['Portador'];
	        $pgtoArray["vcmentoboleto"] = "3";
	        $cpf = $post['CPF'];
	        $pgtoArray['credito_portador_cpf'] = preg_replace("/[^0-9]/", "", $cpf);
	        $pgtoArray['credito_portador_DDD'] = $this->getNumberOrDDD($post['Telefone'], true);
	        $pgtoArray['credito_portador_telefone'] = $this->getNumberOrDDD($post['Telefone']);
	        $pgtoArray['credito_portador_nascimento'] =  date('Y-m-d', strtotime($post['DataNascimento']));
			$model->setMeio_pg($post['forma_de_pagamento']);
			if($post['forma_de_pagamento'] == "BoletoBancario"){
				$model->setbrand_moip('Bradesco');				
			}
			if ($post['forma_de_pagamento'] == "CartaoCredito") {
				$model->setCreditcard_parc($post['parcelas']);
				$model->setbrand_moip($post['bandeira']);
				$model->setfirst6(substr($post['Numero'], 0, 6));
				$model->setLast4(substr($post['Numero'], -4));			
			}
			if($post['forma_de_pagamento'] == "DebitoBancario")
			{				
				$model->setbrand_moip((string)$post['instituicao']);
		       	
		    }
			
			$model->save();
			if($post['forma_de_pagamento'] == "BoletoBancario"){
					$json = array(
						'Forma' => 'BoletoBancario',
					 );
				} elseif ($post['forma_de_pagamento'] == "DebitoBancario") {
					$json = array(
						'Forma' => 'DebitoBancario',
						'Instituicao' => $post['instituicao'],
					 );
					
				} else {

			$json = array(
						'Forma' => $post['forma_de_pagamento'],
						'Instituicao' =>  $post['bandeira'],
						'Parcelas' => $post['parcelas'],
						'CartaoCredito' => array(
										'Numero' => $post['Numero'],
										'Expiracao' => $post['Expiracao_mes']."/".$post['Expiracao_ano'],
										'CodigoSeguranca' => $post['CodigoSeguranca'],
										'Portador' => array(
											'Nome' => $post['Portador'],
											'DataNascimento' =>  date('Y-m-d', strtotime($post['DataNascimento'])),
											'Telefone' => $this->getNumberOrDDD($post['Telefone'], true).$this->getNumberOrDDD($post['Telefone']),
											'Identidade' =>  $cpf,
											),
										),
					);
			}
			echo Mage::helper('core')->jsonEncode((object)$json);
		}
	}
	public function RepagAction() {
		if($this->getRequest()->getParams()){
			$post = $this->getRequest()->getPost();
	        $cpf = preg_replace("/[^0-9]/", "", $post['CPF']);
			$api = Mage::getModel('moip/api');
			$json = array(
						'Forma' => "CartaoCredito",
						'Instituicao' =>  $post['bandeira'],
						'Parcelas' => $post['parcelas'],
						'CartaoCredito' => array(
										'Numero' => $post['Numero'],
										'Expiracao' => $post['Expiracao_mes']."/".$post['Expiracao_ano'],
										'CodigoSeguranca' => $post['CodigoSeguranca'],
										'Portador' => array(
											'Nome' => $post['Portador'],
											'DataNascimento' =>  date('Y-m-d', strtotime($post['DataNascimento'])),
											'Telefone' => $this->getNumberOrDDD($post['Telefone'], true).$this->getNumberOrDDD($post['Telefone']),
											'Identidade' =>  $cpf,
											),
										),
					);
		}
		echo Mage::helper('core')->jsonEncode((object)$json);
		var_dump($json);
		return true;
	}
    function getNumberOrDDD($param_telefone, $param_ddd = false) {

        $cust_ddd = '00';
        $cust_telephone = preg_replace("/[^0-9]/", "", $param_telefone);
        $st = strlen($cust_telephone) - 8;
        if ($st > 0) { //No caso essa seqüência é mais de 8 caracteres
            $cust_ddd = substr($cust_telephone, 0, 2);
            $cust_telephone = substr($cust_telephone, $st, 8);
        }

        if ($param_ddd === false) {
            $retorno = $cust_telephone;
        } else {
            $retorno = $cust_ddd;
        }

        return $retorno;
    }
}