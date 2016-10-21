<?php

namespace PayCheckout\Json\Generic\Invoice;

use PayCheckout\Json\JsonBase;

/**
 * TrackAndTraceInfo
 */
class TrackAndTraceInfo extends JsonBase
{
    /**
     * @var string
     */
    protected $trackingNumber;
    
    /**
     * @var string
     */
    protected $trackingUrl;
    
    /**
     * @var string
     */
    protected $shippingCompany;
    
    /**
     * @var string
     */
    protected $shippingMethod;
    
    /**
     * @var string
     */
    protected $returnTrackingNumber;
    
    /**
     * @var string
     */
    protected $returnTrackingUrl;
    
    /**
     * @var string
     */
    protected $returnShippingCompany;
    
    /**
     * Summary of set
     * @param string $trackingNumber 
     * @param string $trackingUrl 
     * @param string $shippingCompany 
     * @param string $shippingMethod 
     * @param string $returnTrackingNumber 
     * @param string $returnTrackingUrl 
     * @param string $returnShippingCompany 
     */
    public function set($trackingNumber,$trackingUrl,$shippingCompany,$shippingMethod,$returnTrackingNumber,$returnTrackingUrl,$returnShippingCompany)
    {
        $this->trackingNumber           = $trackingNumber;
        $this->trackingUrl              = $trackingUrl;
        $this->shippingCompany          = $shippingCompany;
        $this->shippingMethod           = $shippingMethod;
        $this->returnTrackingNumber     = $returnTrackingNumber;
        $this->returnTrackingUrl        = $returnTrackingUrl;
        $this->returnShippingCompany    = $returnShippingCompany;
    }
    
    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }
    
    /**
     * @return string
     */
    public function getTrackingUrl()
    {
        return $this->trackingUrl;
    }
    
    /**
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->shippingCompany;
    }
      
    /**
     * @return string
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }   
    
    /**
     * @return string
     */
    public function getReturnTrackingNumber()
    {
        return $this->returnTrackingNumber;
    }   
    
    /**
     * @return string
     */
    public function getReturnTrackingUrl()
    {
        return $this->returnTrackingUrl;
    }   
    
    /**
     * @return string
     */
    public function getReturnShippingCompany()
    {
        return $this->returnShippingCompany;
    }   
}
