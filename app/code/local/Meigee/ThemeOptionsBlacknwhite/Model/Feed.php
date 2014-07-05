<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    public function getFeedUrl() {
        $url = 
            Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://'
            . 'www.meigeeteam.com/templates/blacknwhite/notes.rss';
            return $url;
    }
    
    public function observe() {
    	Mage::getModel('Meigee_ThemeOptionsBlacknwhite_Model_Feed')->checkUpdate();
    }

}