<?php
/**
 * Print Science personalization API response on beginPersonalization function
 *
 */
class PrintScience_Personalization_Model_ApiGateway_Response_Begin
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
     * Extracts session key from response.
     *
     * @return string
     */
    public function getSessionKey()
    {
        return $this->response->value()->arrayMem(0)->scalarval();
    }

    /**
     * Extracts personalization app URL from response.
     *
     * @return string
     */
    public function getAppUrl()
    {
        return $this->response->value()->arrayMem(1)->scalarval();
    }
}