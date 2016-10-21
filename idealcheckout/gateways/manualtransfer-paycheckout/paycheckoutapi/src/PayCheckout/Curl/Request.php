<?php

namespace PayCheckout\Curl;

use Exception;

class Request
{
    /**
	 * @var resource
	 */
    private $curlSession;
    
    /**
	 * @var string
	 */
    private $method;
    
    /**
	 * @var string
	 */
    private $contentType;
    
    /**
	 * @var string
	 */
    private $contentLength;
    
    /**
	 * @var string
	 */
    private $content;
    
    /**
	 * @var string[]
	 */
    private $headers;

    /**
     * Create curl request
     *
     * @param string $url
     * @throws Exception
     */
    public function __construct($url)
    {
        // Init curl session
        $this->curlSession = curl_init($url);
        
        if ($this->curlSession !== false)
        {
            // Return the transfer as a string of the return value of curl_exec() instead of outputting it out directly
            curl_setopt($this->curlSession, CURLOPT_RETURNTRANSFER, true);
            // Include header in output
            curl_setopt($this->curlSession, CURLOPT_HEADER, true);
            // Set timeout to 100 seconds
            curl_setopt($this->curlSession, CURLOPT_TIMEOUT, 100);
            // Set connection timeout to 2 seconds
            curl_setopt($this->curlSession, CURLOPT_CONNECTTIMEOUT, 2);
            // Enable all supported encodings?
            curl_setopt($this->curlSession,CURLOPT_ENCODING, ''); 
            
            // FIX: http://stackoverflow.com/questions/6400300/https-and-ssl3-get-server-certificatecertificate-verify-failed-ca-is-ok
            // SSL, verify that the CA certificate is OK 
            curl_setopt($this->curlSession, CURLOPT_SSL_VERIFYPEER, true);
            
			// cacert.pem downloaded from http://curl.haxx.se/ca/cacert.pem
            curl_setopt($this->curlSession, CURLOPT_CAINFO, __DIR__ . DIRECTORY_SEPARATOR . 'cacert.pem'); 
        }
        else
        {
            throw new Exception('Curl initialization failed');
        }
    }
    
    /**
	 * @return string
	 */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
	 * @param string $method 
	 */
    public function setMethod($method)
    {
        $this->method = $method;
    }
    
    /**
	 * @return string
	 */
    public function getContentType()
    {
        return $this->contentType;
    }
    
    /**
	 * @param string $contentType 
	 */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }
    
    /**
	 * @return string
	 */
    public function getContentLength()
    {
        return $this->contentType;
    }
    
    /**
	 * @param string $contentLength
	 */
    public function setContentLength($contentLength)
    {
        $this->contentLength = $contentLength;
    }
    
    /**
	 * @return string
	 */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
	 * @param string $content
	 */
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    /**
	 * @return string[]
	 */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
	 * @param string[] $headers
	 */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
    
    /**
	 * Get response with curl
	 * 
	 * @return Response
	 */
    public function getResponse()
    {
        // Set method
        curl_setopt($this->curlSession, CURLOPT_CUSTOMREQUEST, $this->method);
        
        // Set content
        if ($this->contentType != null && $this->content != null)
        {
            // Add headers for content
            $this->headers[]  = 'Content-Type: ' . $this->contentType;
            $this->headers[]  = 'Content-Length: ' . strlen($this->content);
            
            curl_setopt($this->curlSession, CURLOPT_POSTFIELDS, $this->content);
        }
        
        // Set not to use Expect100Continue as this generates protocol overhead which will slow down operation somewhat
        $this->headers[] = 'Expect:';
        
        // Set headers
        curl_setopt($this->curlSession, CURLOPT_HTTPHEADER, $this->headers);
        
        // Execute curl and create new curl response
        $response = new Response($this->curlSession, curl_exec($this->curlSession));
        
        // Close curl
        curl_close($this->curlSession);
        
        // Return response
        return $response;
    }
}
