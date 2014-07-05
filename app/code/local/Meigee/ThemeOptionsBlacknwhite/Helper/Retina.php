<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Helper_Retina extends Mage_Core_Helper_Abstract
{
 
	public function getRetinaData ($data, $_item=0, $_itemparameter=0) {

 		$helpImg = MAGE::helper('ThemeOptionsBlacknwhite/images');

 		if (Mage::getStoreConfig('meigee_blacknwhite_general/retina/status')) {
			$html = '';
			$general = MAGE::helper('ThemeOptionsBlacknwhite')->getThemeOptionsBlacknwhite('meigee_blacknwhite_general');
			$customlogo = $general['customlogo'];
			switch ($data) {
				case 'logo':
					$html = 'data-srcX2="' . Mage::getDesign()->getSkinUrl('images/@x2/logo@x2.png') . '"';
				break;
				case 'logo_custom':
					$mediaurl = MAGE::helper('ThemeOptionsBlacknwhite')->getThemeOptionsBlacknwhite('mediaurl');
					$html = 'data-srcX2="' . $mediaurl.$customlogo['logo_retina'] . '"';
				break;
				case 'small_logo':
					$html = 'data-srcX2="' . Mage::getDesign()->getSkinUrl('images/@x2/small_logo@x2.png') . '"';
				break;
				case 'small_logo_custom':
					$mediaurl = MAGE::helper('ThemeOptionsBlacknwhite')->getThemeOptionsBlacknwhite('mediaurl');
					$html = 'data-srcX2="' . $mediaurl.$customlogo['small_logo_retina'] . '"';
				break;
				default:
					# code...
					break;
			}
			return $html;
		}
		else return false;
	}

}