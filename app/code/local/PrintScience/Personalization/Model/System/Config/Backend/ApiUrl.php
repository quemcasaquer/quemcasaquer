<?php
/**
 * Personalization API URL backend
 *
 */
class PrintScience_Personalization_Model_System_Config_Backend_ApiUrl extends Mage_Core_Model_Config_Data
{
    /**
     * Check if API URL has trailing '/'. If not, then add it.
     *
     * @return PrintScience_Personalization_Model_System_Config_Backend_ApiUrl
     */
    protected function _beforeSave()
    {
        $apiUrl = $this->getValue();
        if (($apiUrl) && ($apiUrl[strlen($apiUrl) - 1] != '/')) {
            $apiUrl .= '/';
            $this->setValue($apiUrl);
        }
        
        return $this;
    }
}
?>