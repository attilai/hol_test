<?php

namespace PayCheckout;

interface IApiCommunication
{
    /**
     * Do an API request
     * 
     * @param string $url 
     * @param string $method 
     * @param string $contentType 
     * @param string $content
     * @return ApiCommunicationResult
     */
    public function doRequest($url, $method, $contentType, $content);
}