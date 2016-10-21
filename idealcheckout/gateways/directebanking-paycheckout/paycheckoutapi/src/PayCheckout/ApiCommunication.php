<?php

namespace PayCheckout;

use Exception;

class ApiCommunication implements IApiCommunication
{
    /**
	 * Do an API request
	 *
	 * @param string $url 
	 * @param string $method 
	 * @param string $contentType 
	 * @param string $content 
	 *
	 * @return ApiCommunicationResult
	 */
    function doRequest($url, $method, $contentType = null, $content = null)
    {
        $result = new ApiCommunicationResult();

        try
        {
	        $curlRequest = new Curl\Request($url);
        }
        catch (Exception $e)
        {
            $result->setStatus(new Curl\Error(CURLE_FAILED_INIT, $e->getMessage()));
            return $result;
        }
        
        $curlRequest->setMethod($method);
        $curlRequest->setContentType($contentType);
        $curlRequest->setContent($content);
		
        // Execute
        $curlResponse = $curlRequest->getResponse();
		
        // Try to find paycheckouttracereference
        $traceReference = $curlResponse->getHeader('PayCheckoutTraceRef');
        if ($traceReference != null)
        {
            $result->setTraceReference($traceReference);
        }
		
        // Process response
        if ($curlResponse->isSuccess())
        {    
            $result->setResponseCode($curlResponse->getHttpCode());
            $result->setContentType($curlResponse->getContentType());

            $body = $curlResponse->getBody();
            $result->setContent($body);
        } 
        else
        {
            $result->setStatus($curlResponse->getCurlError());
			
            if ($curlResponse->getHttpCode() != 0)
            {
                $result->setResponseCode($curlResponse->getHttpCode());
                $result->setContentType($curlResponse->getContentType());
				
                // Read as much as we can from the error
                $result->setContent($curlResponse->getBody());                   
            }
        }
        
        return $result;
    }
}