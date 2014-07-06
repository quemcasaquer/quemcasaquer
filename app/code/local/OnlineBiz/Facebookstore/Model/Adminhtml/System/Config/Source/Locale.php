<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Adminhtml_System_Config_Source_Locale
{
    public function toOptionArray()
    {
        return Mage::getModel('facebookstore/locale')->getOptionLocales();
    }

}