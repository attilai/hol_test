<?php

namespace PayCheckout\Api\Service\AvailablePaymentMethod;
use PayCheckout\Json\JsonBase;

class AvailablePaymentMethod extends JsonBase
{
    /** 
     *  @var int
     */
    protected $paymentMethod;   

    /** 
     *  @var int
     */
    protected $currency;   
    
    /**
     * @var string
     */
    protected $paymentMethodName;
    
    /**
     * @var string
     */
    protected $availabilityInfo;
    
    /**
     * @var string
     */
    protected $paymentMethodDescription;
    
    /**
     * @var string
     */
    protected $urlPaymentMethodLogo;
    
    /**
     * @var int
     */
    protected $paymentCost;

    /**
     * @var int
     */
    protected $paymentCostExclVat;

    /**
     * @var int
     */
    protected $paymentCostInclVat;
    
    /**
     * @var int
     */
    protected $paymentCostVatDisplayPercentage;

    
    /**
     * @return int
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
    
    /**
     * @return int
     */
    public function getCurrency()
    {
        return $this->currency;
    }
        
    /**
     * @return string
     */
    public function getPaymentMethodName()
    {
        return $this->paymentMethodName;
    }   
    
    /**
     * @return string
     */
    public function getAvailabilityInfo()
    {
        return $this->availabilityInfo;
    }   
    
    /**
     * @return string
     */
    public function getPaymentMethodDescription()
    {
        return $this->paymentMethodDescription;
    }   
    
    /**
     * @return string
     */
    public function getUrlPaymentMethodLogo()
    {
        return $this->urlPaymentMethodLogo;
    }   
    
    /**
     * @return int
     */
    public function getPaymentCost()
    {
        return $this->paymentCost;
    }

    /**
     * @return int
     */
    public function getPaymentCostExclVat()
    {
        return $this->paymentCostExclVat;
    }

    /**
     * @return int
     */
    public function getPaymentCostInclVat()
    {
        return $this->paymentCostInclVat;
    }

    /**
     * @return int
     */
    public function getPaymentCostVatDisplayPercentage()
    {
        return $this->paymentCostVatDisplayPercentage;
    }
    
}
