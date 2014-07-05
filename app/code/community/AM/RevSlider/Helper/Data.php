<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Helper_Data extends Mage_Core_Helper_Abstract{
    public function getCssHref($data){
        if ($data) return sprintf('http://fonts.googleapis.com/css?family=%s', $data);
    }

    public function getCssFromController($path, $params=array()){
        $stores = Mage::app()->getStores();
        foreach($stores as $store){
            $params = array_merge($params, array('_store' => $store->getId()));
            return Mage::getUrl($path, $params);
            break;
        }
    }
}