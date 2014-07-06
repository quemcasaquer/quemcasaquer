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

class OnlineBiz_ObBase_Helper_Data extends Mage_Core_Helper_Abstract
{
	public static function isActivated($module, $key, $generalConfig)
	{
		$servStr = $_SERVER['HTTP_HOST'];
		$servStr = str_replace('https://', '', $servStr);
		$servStr = str_replace('http://', '', $servStr);
		$servStr = str_replace('www.', '', $servStr);
		if((preg_match('/dev./',$servStr) || preg_match('/test./',$servStr) || preg_match('/demo./',$servStr)) && Mage::getStoreConfig($generalConfig))
			return true;
		if(($servStr == '127.0.0.1' || preg_match('/localhost/',$servStr)) && Mage::getStoreConfig($generalConfig))
			return true;
		if(base64_encode(md5($servStr.$module)) == $key && Mage::getStoreConfig($generalConfig))
			return true;
		return false;
	}
}


