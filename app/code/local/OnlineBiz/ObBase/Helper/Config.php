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

class OnlineBiz_ObBase_Helper_Config extends Mage_Core_Helper_Abstract{
	/** Extensions feed path */
    const EXTENSIONS_FEED_URL = 'http://store.onlinebizsoft.com/feeds/extensions.xml';
	/** Updates Feed path */
    const UPDATES_FEED_URL = 'http://store.onlinebizsoft.com/feeds/updates.xml';
	    /** Store URL */
    const STORE_URL = 'http://store.onlinebizsoft.com/store/';

    /** Store response cache key*/
    const STORE_RESPONSE_CACHE_KEY = 'onlinebiz_store_response_cache_key';
}
