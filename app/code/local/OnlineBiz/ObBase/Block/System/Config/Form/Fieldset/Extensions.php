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


class OnlineBiz_ObBase_Block_System_Config_Form_Fieldset_Extensions
	extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
	protected $_dummyElement;
	protected $_fieldRenderer;
	protected $_values;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
		$html = $this->_getHeaderHtml($element);
		$modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
		sort($modules);

        foreach ($modules as $moduleName) {
        	if (strstr($moduleName,'OnlineBiz_') === false) {
        		continue;
        	}
			
			if($moduleName == 'OnlineBiz_Core' || $moduleName == 'OnlineBiz_ObBase'){
				continue;
			}
			
        	$html.= $this->_getFieldHtml($element, $moduleName);
        }
        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    protected function _getDummyElement()
    {
    	if (empty($this->_dummyElement)) {
    		$this->_dummyElement = new Varien_Object(array('show_in_default'=>1, 'show_in_website'=>1));
    	}
    	return $this->_dummyElement;
    }

    protected function _getFieldRenderer()
    {
    	if (empty($this->_fieldRenderer)) {
    		$this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
    	}
    	return $this->_fieldRenderer;
    }

	protected function _getFieldHtml($fieldset, $moduleName)
    {
		$configData = $this->getConfigData();
    	$path = 'advanced/modules_disable_output/'.$moduleName; //TODO: move as property of form
    	$data = isset($configData[$path]) ? $configData[$path] : array();

    	$e = $this->_getDummyElement();

		$moduleKey = substr($moduleName, strpos($moduleName,'_')+1);
		$ver = (Mage::getConfig()->getModuleConfig($moduleName)->version);
		$id = $moduleName;
		
		$hasUpdate = false;
		if($displayNames = Mage::app()->loadCache('base_extensions_feed')){
			if($displayNames = unserialize($displayNames)){
				if(isset($displayNames[$moduleName])){
					$url = @$displayNames[$moduleName]['url'];
					$name = @$displayNames[$moduleName]['display_name'];
					$version = @$displayNames[$moduleName]['version'];
				
					$moduleName = '<a href="'.$url.'" target="_blank" title="'.$name.'">'.$name."</a>";
					
					
					if($this->_convertVersion($ver) < $this->_convertVersion($version)){
						$update = '<a href="'.$url.'" target="_blank"><img src="'.$this->getSkinUrl('onlinebizsoft/obbase/images/update.gif').'" title="'.$this->__("Update available").'"/></a>';
						$hasUpdate = 1;
						$moduleName ="$update $moduleName";
					}
				}
			}
		}
	
		if(!$hasUpdate){
			$update = '<a  target="_blank"><img src="'.$this->getSkinUrl('onlinebizsoft/obbase/images/ok.gif').'" title="'.$this->__("Installed").'"/></a>';
			$moduleName ="$update $moduleName";
		}
	
		if($ver){
			
			
			
			$field = $fieldset->addField($id, 'label',
				array(
					'name'          => 'ssssss',
					'label'         => $moduleName,
					'value'         => $ver,
					
				))->setRenderer($this->_getFieldRenderer());
			return $field->toHtml();
		}
		return '';
		
    }
    
    
    
    protected function _convertVersion($v){
		$digits = @explode(".", $v);
		$version = 0;
		if(is_array($digits)){
			foreach($digits as $k=>$v){
				$version += ($v * pow(10, max(0, (3-$k))));
			}
			
		}
		return $version;
	}
}
