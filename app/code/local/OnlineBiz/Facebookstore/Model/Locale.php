<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Locale
{
    public function getLocales()
    {
    	$locales = array();
    	$localesFile = Mage::app()->getConfig()->getModuleDir('etc', 'OnlineBiz_Facebookstore').DS.'FacebookLocales.xml';
		
    	$xml = simplexml_load_file($localesFile, null, LIBXML_NOERROR);
		if($xml && is_object($xml->locale)) {
			foreach($xml->locale as $item) {
        		$locales[(string)$item->codes->code->standard->representation] = (string)$item->englishName;
			}
        }   	
    	
        asort($locales);
    	return $locales;
    }
    
	public function getOptionLocales()
    {
    	$locales = array();
    	foreach($this->getLocales() as $value => $label) {
    		$locales[] = array(
				'value' => $value,
				'label' => $label  	
    		);	
    	}
    	return $locales;
    }
    
}