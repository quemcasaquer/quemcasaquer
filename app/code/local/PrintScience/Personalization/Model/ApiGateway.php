<?php
require_once 'xmlrpc/xmlrpc.inc';

/**
 * Gateway to Print Science personalization API
 *
 */
class PrintScience_Personalization_Model_ApiGateway
{
    /**
     * Calls beginPersonalization API function and returns response.
     *
     * @param int $templateId
     * @param string $successUrl
     * @param string $failUrl
     * @param string $cancelUrl
     * @return PrintScience_Personalization_Model_ApiGateway_Response_Begin
     */
    public function beginPersonalization($templateId, $successUrl, $failUrl, $cancelUrl)
    {
        $apiUrl = Mage::getStoreConfig('catalog/personalization/api_url');
        $apiVersion = Mage::getStoreConfig('catalog/personalization/api_version');
		$magelocale = Mage::app()->getLocale()->getLocaleCode();
		$localeParts = explode('_',$magelocale);
		$locale=$localeParts[0];
        $client = new xmlrpc_client($apiUrl);
        $function = null;
        // check api version
        switch($apiVersion)
        {
            case '1.0.0':
                $function = new xmlrpcmsg('beginPersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode('')
                ));
                break;
            case '2.0.0':
                $function = new xmlrpcmsg('beginPersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode(''),
                    php_xmlrpc_encode('en'),
                    php_xmlrpc_encode(''),
                    php_xmlrpc_encode('')
                ));
                break;
            case '4.0.0':
               $function = new xmlrpcmsg('beginPersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode(''),
					php_xmlrpc_encode($locale)
                ));
                break;
            default:
                $function = new xmlrpcmsg('beginPersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode('')
                ));
                break;
        }
        $response = $client->send($function);

        return new PrintScience_Personalization_Model_ApiGateway_Response_Begin($response);
    }

    /**
     * Calls getPreview API function and returns response.
     *
     * @param string $sessionKey
     * @return PrintScience_Personalization_Model_ApiGateway_Response_GetPreview
     */
    public function getPreview($sessionKey)
    {
        $client = new xmlrpc_client(Mage::getStoreConfig('catalog/personalization/api_url'));

        $function = new xmlrpcmsg('getPreview', array(
        php_xmlrpc_encode($sessionKey)
        ));

        $response = $client->send($function);

        return new PrintScience_Personalization_Model_ApiGateway_Response_GetPreview($response);
    }

    /**
     * Calls endPersonalization API function.
     *
     * @param string $sessionKey
     */
    public function endPersonalization($sessionKey)
    {
        $client = new xmlrpc_client(Mage::getStoreConfig('catalog/personalization/api_url'));

        $function = new xmlrpcmsg('endPersonalization', array(
        php_xmlrpc_encode($sessionKey)
        ));

        $client->send($function);
    }

/**
     * Calls resumePersonalization API function and returns response.
     *
     * @param int $templateId
     * @param string $successUrl
     * @param string $failUrl
     * @param string $cancelUrl
     * @return PrintScience_Personalization_Model_ApiGateway_Response_Begin
     */
    public function resumePersonalization($sessionKey, $templateId, $successUrl, $failUrl, $cancelUrl)
    {
        $apiUrl = Mage::getStoreConfig('catalog/personalization/api_url');
        $apiVersion = Mage::getStoreConfig('catalog/personalization/api_version');
        $client = new xmlrpc_client($apiUrl);
        $function = null;
        // check api version
        switch($apiVersion)
        {
            case '1.0.0':
                $function = new xmlrpcmsg('resumePersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($sessionKey),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode('')
                ));
                break;
            case '2.0.0':
                $function = new xmlrpcmsg('resumePersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($sessionKey),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode(''),
                    php_xmlrpc_encode('')
                ));
                break;
            case '4.0.0':
                $function = new xmlrpcmsg('resumePersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($sessionKey),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode(''),
                    php_xmlrpc_encode('')
                ));
                break;
            default:
                $function = new xmlrpcmsg('resumePersonalization', array(
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_username')),
                    php_xmlrpc_encode(Mage::getStoreConfig('catalog/personalization/api_password')),
                    php_xmlrpc_encode($sessionKey),
                    php_xmlrpc_encode($templateId),
                    php_xmlrpc_encode($successUrl),
                    php_xmlrpc_encode($failUrl),
                    php_xmlrpc_encode($cancelUrl),
                    php_xmlrpc_encode('')
                ));
                break;
        }
        $response = $client->send($function);

        return new PrintScience_Personalization_Model_ApiGateway_Response_Begin($response);
    }    
}