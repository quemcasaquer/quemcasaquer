<?php
class O2TI_Moip_Block_IndexController_Novaforma extends Mage_Core_Block_Template{
    public function __construct(){
		parent::__construct();
		$this->loadLayout();
		$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('O2TI_Moip_Block_Standard_Novaforma'));
		$this->renderLayout();
	}
	public function mostraCartao()
	{
		
	}
}	
?>