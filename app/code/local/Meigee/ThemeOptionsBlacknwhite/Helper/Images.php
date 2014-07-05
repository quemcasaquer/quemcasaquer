<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2013 - 2014 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Helper_Images extends Mage_Core_Helper_Abstract
{
	public function getImg($_product, $imgType = "image", $w, $h, $file = NULL)
	{
		$config = Mage::getStoreConfig('meigee_blacknwhite_general/productimages');
		$keepAspectRatio = $config['keepAspectRatio'];

		if ($config['reallyCustomAspectRatio']) {
			$customAspectRatio = $config['reallyCustomAspectRatio'];
		} else $customAspectRatio = $config['customAspectRatio'];
		
		$url = Mage::helper('catalog/image')
				->init($_product, $imgType, $file);

		if ($keepAspectRatio) {
			$url->keepAspectRatio(TRUE);
			$h = NULL;
		} else $url->keepAspectRatio(FALSE);

		return $url->constrainOnly(TRUE)
			->keepFrame(FALSE)
			->resize($w, $w*$customAspectRatio);
	}

	public function getImgSources ($_product, $imgType = "image", $w, $h, $file = NULL) 
	{
		$html = "src=\"" . $this->getImg ($_product, $imgType, $w, $h, $file);
 		if (Mage::getStoreConfig('meigee_blacknwhite_general/retina/status')) {
 			$html .= "\" data-srcX2=\"";
 			$html .= $this->getImg ($_product, $imgType, $w*2, $h*2, $file);
 		}
		$html .= "\"";
		return $html;
	}

	public function getHoverImage ($_product, $imgType = "small_image", $w, $h, $file = NULL) {
		$rollover = MAGE::helper('ThemeOptionsBlacknwhite')->getThemeOptionsBlacknwhite('meigee_blacknwhite_general');
		if ($rollover['rollover']['rollover_status'] == true):
			$html = "";
		 	$imgcount = Mage::getModel('catalog/product')->load($_product->getId())->getMediaGalleryImages()->count();
		 	if ($imgcount>0):
		 		$_gallery = Mage::getModel('catalog/product') -> load($_product -> getId()) -> getMediaGalleryImages();
			 	foreach ($_gallery as $_image ):
			        if ($_image->getLabel() == 'hover'):
			        	$html = '<span class="hover-image"><img ' . $this->getImgSources($_product, $imgType, $w, $h, $_image -> getFile()) . ' /></span>';
			 		break;
			    	endif;
		        endforeach;
			endif;
			return $html;
		endif;
	}
}