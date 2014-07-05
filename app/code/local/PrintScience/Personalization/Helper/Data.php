<?php
/**
 * Print Science personalization data helper
 *
 */
class PrintScience_Personalization_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Personalization redirect action handler.
     *
     * @return boolean
     */
	public function _redirectToUrl($url, $params='0') {
		$str="";
		if($params=='1') {
			$str = "?added=1";
			//echo "ininini";
			//exit;
		}
		echo '<html><head><script type="text/javascript">window.parent.location.href = "'.$url.$str.'";</script></head><body></body></html';
		return false;
	}
}