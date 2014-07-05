<?php
/**
 * Print Science personalization API response on getPreview function
 *
 */
class PrintScience_Personalization_Model_ApiGateway_Response_GetPreview
extends PrintScience_Personalization_Model_ApiGateway_Response_Abstract
{
    /**
     * Constructor
     *
     * @param xmlrpcresp $response
     */
    public function __construct($response)
    {
        parent::__construct($response);
    }

    /**
     * Extracts URL to the PDF proof from response
     *
     * @return string
     */
    public function getPdfUrl()
    {
        return $this->response->value()->structMem('pdf_url')->scalarval();
    }

    /**
     * Extracts URLs to preview images from response
     *
     * @return array
     */
    public function getPeviewUrls()
    {
        $previewUrls = array();
        $apiVersion = Mage::getStoreConfig('catalog/personalization/api_version');
        $previewUrlMember = $this->response->value()->structMem('preview_url');
        // check api version
        switch($apiVersion)
        {
            case '4.0.0':
                for ($i = 0; $i < $previewUrlMember->arraySize(); $i++) {
                    $temp = $previewUrlMember->arrayMem($i)->scalarval();
                    $previewUrls[] = $temp[1]->scalarval();
                }
                break;
            default:
                for ($i = 0; $i < $previewUrlMember->arraySize(); $i++) {
                    $previewUrls[] = $previewUrlMember->arrayMem($i)->scalarval();
                }
                break;
        }
        return $previewUrls;
    }
}