<?php
/**
 * Extensions Manager Extension
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://store.onlinebizsoft.com/license.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to admin@onlinebizsoft.com so we can mail you a copy immediately.
 *
 * @category   Magento Extensions
 * @package    ExtensionManager
 * @author     OnlineBiz <sales@onlinebizsoft.com>
 * @copyright  2007-2011 OnlineBiz
 * @license    http://store.onlinebizsoft.com/license.txt
 * @version    1.0.1
 * @link       http://store.onlinebizsoft.com
 */

class OnlineBiz_ObBase_Model_Source_Updates_Type extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{
    
	const TYPE_PROMO = 'PROMO';
	const TYPE_NEW_RELEASE = 'NEW_RELEASE';
	const TYPE_UPDATE_RELEASE = 'UPDATE_RELEASE';
	const TYPE_INFO = 'INFO';
	const TYPE_INSTALLED_UPDATE = 'INSTALLED_UPDATE';
	
	
	public function toOptionArray(){
		return array(
			array('value' => self::TYPE_INSTALLED_UPDATE, 'label' => Mage::helper('obbase')->__('My extensions updates')),
			array('value' => self::TYPE_UPDATE_RELEASE, 'label' => Mage::helper('obbase')->__('All extensions updates')),
			array('value' => self::TYPE_NEW_RELEASE, 'label' => Mage::helper('obbase')->__('New Releases')),
			array('value' => self::TYPE_PROMO, 'label' => Mage::helper('obbase')->__('Promotions/Discounts')),
			array('value' => self::TYPE_INFO, 'label' => Mage::helper('obbase')->__('Other information'))
		);
	}
	
	/**
     * Retrive all attribute options
     *
     * @return array
     */
    public function getAllOptions()
    {
    	return $this->toOptionArray();
	}
	
	
	/**
	 * Returns label for value
	 * @param string $value
	 * @return string
	 */
	public function getLabel($value){
		$options = $this->toOptionArray();
		foreach($options as $v){
			if($v['value'] == $value){
				return $v['label'];
			}
		}
		return '';
	}
	
	/**
	 * Returns array ready for use by grid
	 * @return array 
	 */
	public function getGridOptions(){
		$items = $this->getAllOptions();
		$out = array();
		foreach($items as $item){
			$out[$item['value']] = $item['label'];
		}
		return $out;
	}
}
