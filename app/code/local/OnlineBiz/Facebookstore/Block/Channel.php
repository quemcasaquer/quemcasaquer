<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Block_Channel extends OnlineBiz_Facebookstore_Block_Template
{

    protected function _toHtml()
    {
		return '<script src="'.($this->isSecure() ? 'https://' : 'http://').'connect.facebook.net/'.($this->getData('locale') ?  $this->getData('locale') : $this->getLocale()).'/all.js"></script>';
    }

}