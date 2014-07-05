<?php
/**
 * Print Science personalization session data helper
 *
 */
class PrintScience_Personalization_Helper_Session extends Mage_Core_Helper_Abstract 
{
    /**
     * Generate and return personalization session key
     *
     * @return string
     */
    public function generateKey()
    {
        return md5(uniqid(null, true));
    }
    
    /**
     * Get personalization session data that corresponds to provided key
     *
     * @param string $key
     * @return array | false
     */
    public function getData($key)
    {
        $session = Mage::getSingleton('core/session');
        
        if ($session->hasData('productPersonalization')) {
            $data = $session->getData('productPersonalization');
            if (isset($data[$key])) {
                return $data[$key];
            }
        }
        return false;
    }
    
    /**
     * Add personalization session data to session object
     *
     * @param string $key
     * @param array $data
     */
    public function addData($key, $data)
    {
        $session = Mage::getSingleton('core/session');
        
        $currentData = $session->getData('productPersonalization');
        $currentData[$key] = $data;
        $session->setData('productPersonalization', $currentData);
    }
    
    /**
     * Remove personalization session data from session object
     *
     * @param string $key
     */
    public function unsetData($key)
    {
        $session = Mage::getSingleton('core/session');
        
        if ($session->hasData('productPersonalization')) {
            $data = $session->getData('productPersonalization');
            if (isset($data[$key])) {
                unset($data[$key]);
                $session->setData('productPersonalization', $data);
            }            
        }
    }
}