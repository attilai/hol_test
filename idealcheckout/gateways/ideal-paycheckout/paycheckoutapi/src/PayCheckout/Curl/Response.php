<?php

namespace PayCheckout\Curl;

class Response
{
    /**
	 * @var bool
	 */
    private $success;
    
    /**
	 * @var int
	 */
    private $httpCode;
    
    /**
	 * @var Error
	 */
    private $curlError;
    
    /**
	 * @var string
	 */
    private $contentType;
    
    /**
	 * @var string
	 */
    private $characterSet;
    
    /**
	 * @var string[]
	 */
    private $headers;
    
    /**
	 * @var string
	 */
    private $body;
    
    /**
	 * Create curl response
	 * 
	 * @param resource $ch 
	 * @param string $response 
	 */
    public function __construct($ch, $response)
    {
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
        if (curl_errno($ch) == CURLE_OK)
        {
            $this->success = true;
        } 
        else
        {
            $this->success = false;
            $curlError = new Error(curl_errno($ch), curl_error($ch));
            $this->curlError = $curlError;
        }
        
        $this->contentType	= $this->getContentTypeFromResponse($ch);
        $this->characterSet	= $this->getCharacterSetFromResponse($ch);
        $this->headers      = $this->getHeadersFromResponse($response);
        $this->body         = $this->getBodyFromResponse($response);
    }
    
    /**
	 * @return boolean
	 */
    public function isSuccess()
    {
        return $this->success;
    }
    
    /**
	 * @return string
	 */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
    
    /**
	 * @return Error
	 */
    public function getCurlError()
    {
        return $this->curlError;
    }
    
    /**
	 * @return string
	 */
    public function getContentType()
    {
        return $this->contentType;
    }
    
    /**
	 * @return string
	 */
    public function getCharacterSet()
    {
        return $this->characterSet;
    }
    
    /**
	 * @return string[]
	 */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
	 * @return string
	 */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
	 * Get the value of a header
	 * 
	 * @param string $name 
	 * @return string|null
	 */
    public function getHeader($name)
    {
        $headers = $this->getHeaders();
        
        if (array_key_exists($name, $headers))
        {
            return $headers[$name];
        }
        else
        {
            return null;
        }
    }
    
    /**
	 * Get the content type of the response
	 * 
	 * @param resource $ch 
	 * @return string
	 */
    private function getContentTypeFromResponse($ch)
    {
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        
        if ($contentType !== null)
        {
            $contentType = explode(';', $contentType);
            
            if (isset($contentType[0]))
            {
                return $contentType[0];
            }
        }
        
        return null;
    }
    
    /**
	 * Get the character set from the response
	 * 
	 * @param resource $ch 
	 * @return string
	 */
    private function getCharacterSetFromResponse($ch)
    {
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        
        if ($contentType !== null)
        {
            preg_match('~charset=([-a-z0-9_]+)~i', $contentType, $characterSet);
            
            if (isset($characterSet[1]))
            {
                return $characterSet[1];
            }
        }
        
        return null;
        
    }
    
    /**
	 * Get the body of the response
	 * 
	 * @param string $response
	 * @return string
	 */
    private function getBodyFromResponse($response)
    {
        $response = $this->splitResponse($response);
        if (count($response) < 2)
        {
            // Less than 2 elements, only headers, no body
            return null;
        }
        else
        {
            // The first element(s) are the headers, the last element of the array will be the body
            return $response[count($response)-1];
        }
    }

    /**
	 * Get the header(s) of the response
	 * 
	 * http://stackoverflow.com/a/18682872
	 * 
	 * @param string $response
	 * @return array
	 */
    private function getHeadersFromResponse($response)
    {
        $headers = array();
        $response = $this->splitResponse($response);

        if (count($response) == 0)
        {
            // No response, no headers
            return $headers;
        }
        else
        {
            // If there is only one element, there is no body and only headers, take the first item
            // If the response is splitted in more than 1 item, get the second to last one, the last will be the body
            $header = count($response) == 1 ? $response[0] : $response[count($response) - 2];
        }
        
        // Get all headers from response header
        foreach (explode("\r\n", $header) as $i => $line)
        {
            if ($i === 0)
            {
                $headers['http_code'] = $line;
            }
            else
            {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return $headers;
    }
    
    /**
	 * Split the response in header(s) and body
	 * The first element(s) are the headers, the last element of the array will be the body
	 * 
	 * http://stackoverflow.com/a/9183272
	 * 
	 * @param string $response
	 * @return string[]
	 */
    private function splitResponse($response)
    {
        return explode("\r\n\r\n", $response);
    }
}
