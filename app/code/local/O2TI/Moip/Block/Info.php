<?php

class O2TI_Moip_Block_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('O2TI/moip/info.phtml');
    }

   
	
    public function getMoip()
    {
        return Mage::getSingleton('moip/api');
    }

   
    public function getBoletoImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/boleto');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Bradesco.png');
        }
    }

    //imagens de transferencia
    public function getBBImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_bb');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/BancoDoBrasil.png');
        }
    }
    public function getBradescoImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_bradesco');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Bradesco.png');
        }
    }
    public function getItauImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_itau');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Itau.png');
        }
    }
    public function getBanrisulImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_banrisul');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Banrisul.png');
        }
    }

    //imagens de cartao
    public function getVisaImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_visa');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Visa.png');
        }
    }
    public function getMastercardImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_master');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Mastercard.png');
        }
    }
    public function getDinersImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_diners');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Diners.png');
        }
    }
    public function getAmericanExpressImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_american');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/AmericanExpress.png');
        }
    }
    public function getHipercardImage() {
        if (Mage::getStoreConfig('o2tiall/config/trocar_bandeira_cartao')) {
            return Mage::getBaseUrl('media') . "o2ti/allmoip/". Mage::getStoreConfig('o2tiall/config/cartao_hipercard');
        }else {
            return $this->getSkinUrl('O2TI/moip/imagem/Hipercard.png');
        }
    }

    public function getImagePg($param){
        $nome ="";
       switch ($param) {
        case "Visa":
            $nome = $this->getVisaImage();
            break;
        case "Mastercard":
            $nome = $this->getMastercardImage();
            break;
        case "AmericanExpress":
            $nome = $this->getAmericanExpressImage();
            break;
        case "Dinners":
            $nome = $this->getDinersImage();
            break;
        case "Hipercard":
            $nome = $this->getHipercardImage();
            break;
        default:
            $nome = $param;
            break;
        }
        return $nome;

    }
	private function getNomePagamento($param) {
		$nome = "";
		switch ($param) {
		case "BoletoBancario":
		    $nome = "Boleto Bancário";
		    break;
		case "DebitoBancario":
		    $nome = "Transferência Bancária";
		    break;
		case "CartaoCredito":
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
                $dados = array();
                $dados['result_meio'] = $this->getNomePagamento($result->getMeio_pg());
                $dados['meio_pago'] = $this->getNomePagamento($result->getMeio_pg());
                $dados['realorder_id'] = $result->getRealorder_id();
                $dados['order_idmoip'] = $result->getorder_idmoip(); 
                $dados['boleto_line'] = $result->getBoleto_line();
                $dados['customer_id'] = $result->getCustomerId();
                $dados['brand'] = $result->getBrand();
                $dados['creditcard_parc'] = $result->getCreditcard_parc();
                $dados['first6'] = $result->getFirst6();
                $dados['last4'] = $result->getLast4();
                $dados['token'] = $result->getToken();
                $dados['image'] = $this->getImagePg($result->getBrandMoip());
                $dados['url'] = $result->getUrlcheckout_pg();
            return $dados;
    }
}
