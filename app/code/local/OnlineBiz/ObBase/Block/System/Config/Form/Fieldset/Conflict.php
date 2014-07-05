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


class OnlineBiz_ObBase_Block_System_Config_Form_Fieldset_Conflict
	extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
	protected $_dummyElement;
	protected $_fieldRenderer;
	protected $_values;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
		$html = $this->_getHeaderHtml($element);
		$html .= '<table cellpadding="5" cellspacing="5">';
		//retrieve all config.xml
		$tConfigFiles = $this->getConfigFilesList();
		
		//parse all config.xml
		$rewrites = array();
		foreach($tConfigFiles as $configFile)
		{
			$rewrites = $this->getRewriteForFile($configFile, $rewrites);
		}
		$i = 0;
		foreach($rewrites as $key => $value)
		{
			$i++;
			$t = explode('/', $key);
			$moduleName = $t[0];
			$className = $t[1];
			$rewriteClasses = join(', ', $value);
			$conflict = 0;
			if (count($value) > 1)
				$conflict = 1;
				
			$html.= $this->_getFieldHtml($element, $moduleName, $className, $rewriteClasses, $conflict);
		}
		$html .= '</table>';
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

	protected function _getFieldHtml($fieldset, $moduleName, $className, $rewriteClasses, $conflict)
    {
		$html = '<tr>';
		if($conflict)
			$conflict = '<font color="red">'.Mage::helper('obbase')->__("Yes").'</font>';
		else
			$conflict = Mage::helper('obbase')->__("No");
			
		$html .='<td width="80">'.$moduleName.'</td><td>'.$className.'</td><td>'.$rewriteClasses.'</td><td>'.$conflict.'</td>';
		$html .= '</tr>';
		return $html;
		
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
	/**
	 * 
	 *
	 * @param unknown_type $dirName
	 * @return unknown
	 */
	private function directoryIsValid($dirName)
	{
		switch ($dirName) {
			case '.':
			case '..':
			case '':
				return false;
				break;		
			default:
				return true;
				break;
		}
	}
	
	private function manageModule($moduleName)
	{
		switch ($moduleName) {
			case 'global':
				return false;
				break;		
			default:
				return true;
				break;
		}		
	}
	/**
	 * create an array with all config.xml files
	 *
	 */
	public function getConfigFilesList()
	{
		$retour = array();
		//$codePath = Mage::getStoreConfig('system/filesystem/code');
		$codePath = BP . DS . 'app' . DS . 'code';
		$tmpPath = Mage::app()->getConfig()->getTempVarDir().'/obexconflict/';
		if (!is_dir($tmpPath))
			mkdir($tmpPath);
		
		$locations = array();
		$locations[] = $codePath.'/local/';
		$locations[] = $codePath.'/community/';
		$locations[] = $tmpPath;
		
		foreach ($locations as $location)
		{
			//parse every sub folders (means extension folders)
			$poolDir = opendir($location);
			while($namespaceName = readdir($poolDir))
			{
				if (!$this->directoryIsValid($namespaceName))
					continue;
					
				//parse modules within namespace
				$namespacePath = $location.$namespaceName.'/';
				$namespaceDir = opendir($namespacePath);
				while($moduleName = readdir($namespaceDir))
				{
					if (!$this->directoryIsValid($moduleName))
						continue;
					
					$modulePath = $namespacePath.$moduleName.'/';
					$configXmlPath = $modulePath.'etc/config.xml';
					
					if (file_exists($configXmlPath))
						$retour[] = $configXmlPath;
				}
				closedir($namespaceDir);
			}
			closedir($poolDir);
		}
		
		return $retour;
	}
	/**
	 * Return all rewrites for a config.xml
	 *
	 * @param unknown_type $configFilePath
	 */
	public function getRewriteForFile($configFilePath, $results)
	{
		//load xml
		$xmlcontent = file_get_contents($configFilePath);
		$domDocument = new DOMDocument();
		$domDocument->loadXML($xmlcontent);
		
		foreach($domDocument->documentElement->getElementsByTagName('rewrite') as $markup)
		{
			//parse child nodes
			$moduleName = $markup->parentNode->tagName;
			if ($this->manageModule($moduleName))
			{
				foreach($markup->getElementsByTagName('*') as $childNode)
				{
					//get information
					$className = $childNode->tagName;
					$rewriteClass = $childNode->nodeValue; 
					
					//add to result
					$key = $moduleName.'/'.$className;
					if (!isset($results[$key]))
						$results[$key] = array();
					$results[$key][] = $rewriteClass;
					
				}
			}
		}
		
		return $results;
	}
}
