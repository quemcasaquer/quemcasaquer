<?php

class O2TI_Moip_Block_Standard_Info extends Mage_Payment_Block_Info_Container
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('O2TI/moip/info.phtml');
    }

   
	
    public function getMoip()
    {
        return Mage::getSingleton('moip/standard');
    }

   

	private function getNomePagamento($param) {
		$nome = "";
		switch ($param) {
		case "BoletoBancario":
		    $nome = "Boleto Bancário";
		    break;
		case "DebitoBancario":
		    $nome = "Débito Bancário";
		    break;
		case "CartaoDeCredito":
		    $nome = "Cartão de Crédito";
		    break;
		}
		return $nome;
	}


    protected function _prepareInfo()
    {
            
                $order_get = Mage::app()->getRequest()->getParam('order_id');               
                $order = $this->getInfo()->getOrder();
                $order =  $order->getIncrementId();
                $model = Mage::getModel('moip/write');
                $result = $model->load($order, 'realorder_id');
                $meio_forma = json_decode($result->getbrand_moip());
                $dados = array();
                $dados['result_meio'] = $result->getMeio_pg();
                $dados['realorder_id'] = $result->getRealorder_id();
                $dados['brand'] = $result->getBrand();
                $dados['creditcard_parc'] = $result->getCreditcard_parc();
                $dados['first6'] = $result->getFirst6();
                $dados['last4'] = $result->getLast4();
                $dados['url'] = $result->getUrlcheckout_pg();
            return $dados;
    }
}
