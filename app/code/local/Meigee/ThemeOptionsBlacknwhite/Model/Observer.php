<?php
/**
 * Call actions after configuration is saved
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Observer
{
	/**
     * After any system config is saved
     */
	public function cssgenerate()
	{
		$section = Mage::app()->getRequest()->getParam('section');

		if ($section == 'meigee_blacknwhite_design')
		{
			Mage::getSingleton('ThemeOptionsBlacknwhite/Cssgenerate')->saveCss();
			$response = Mage::app()->getFrontController()->getResponse();
			$response->sendResponse();
		}

	}
}
