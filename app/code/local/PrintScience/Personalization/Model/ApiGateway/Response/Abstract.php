<?php
/**
 * Base class for Print Science personalization API responses
 *
 */
class PrintScience_Personalization_Model_ApiGateway_Response_Abstract
{
    /**
     * Response returned by personalization API function
     *
     * @var xmlrpcresp
     */
    protected $response;

    /**
     * Constructor
     *
     * @param xmlrpcresp $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Returns response fault code
     *
     * @return int
     */
    public function getFaultCode()
    {
        return $this->response->faultCode();
    }

    /**
     * Returns response fault string
     *
     * @return string
     */
    public function getFaultString()
    {
        return $this->response->faultString();
    }
}