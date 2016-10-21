<?php

namespace PayCheckout;

use PayCheckout\Curl\Error;

class ApiCommunicationResult
{
    /**
	 * @var Error
	 */
    private $status;
    
    /**
	 * @var int
	 */
    private $responseCode;
    
    /**
	 * @var string
	 */
    private $contentType;
    
    /**
	 * @var string
	 */
    private $content;
    
    /**
	 * @var int|string
	 */
    private $traceReference;
    
    /**
	 * @return Error
	 */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
	 * @param Error $status 
	 */
    public function setStatus(Error $status)
    {
        $this->status = $status;
    }

    /**
	 * @return int
	 */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
    
    /**
	 * @param int $responseCode 
	 */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
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
	 * @return int|string
	 */
    public function getTraceReference()
    {
        return $this->traceReference;
    }
    
    /**
	 * @param int|string $traceReference
	 */
    public function setTraceReference($traceReference)
    {
        $this->traceReference = $traceReference;
    }
    
}