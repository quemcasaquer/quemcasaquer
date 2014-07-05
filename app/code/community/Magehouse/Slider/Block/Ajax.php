<?php 

class Magehouse_Slider_Block_Ajax extends Mage_Core_Block_Template{
	public function __construct(){
		if(Mage::getStoreConfig('price_slider/price_slider_settings/slider_loader_active')){
			$this->config = Mage::getStoreConfig('price_slider');
			$this->url = Mage::getStoreConfig('web/unsecure/base_url');
			
			$this->ajaxSlider = $this->config['ajax_conf']['slider'];
			$this->ajaxLayered = $this->config['ajax_conf']['layered'];
			if(isset($this->config['ajax_conf']['toolbar'])){
				$this->ajaxToolbar = $this->config['ajax_conf']['toolbar'];
			}
			$this->overlayColor = $this->config['ajax_conf']['overlay_color'];
			$this->overlayOpacity = $this->config['ajax_conf']['overlay_opacity'];
			$this->loadingText = $this->config['ajax_conf']['loading_text'];
			$this->loadingTextColor = $this->config['ajax_conf']['loading_text_color'];
			if(isset($this->config['ajax_conf']['loading_image'])){
				$this->loadingImage = $this->config['ajax_conf']['loading_image'];
			}
			if($this->loadingImage == '' || $this->loadingImage == null){
				$this->loadingImage = $this->url.'media/magehouse/slider/default/ajax-loader.gif';
			}else{
				$this->loadingImage = $this->url.'media/magehouse/slider/default/'.$loadingImage;
			}
		}
	}
	
	public function getCallbackJs(){
		return Mage::getStoreConfig('price_slider/ajax_conf/afterAjax');
	}
}