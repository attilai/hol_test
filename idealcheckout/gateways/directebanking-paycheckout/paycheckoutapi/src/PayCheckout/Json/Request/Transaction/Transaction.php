<?php

namespace PayCheckout\Json\Request\Transaction;

use PayCheckout\Json\JsonBase;

class Transaction extends JsonBase
{
    /**
     * @var int
     */
    protected $paymentMethod;
    
    /**
     * @var int
     */
    protected $currency;
    
    /**
     * @var int
     */
    protected $amount;
	
	/**
     * @var int
     */
	protected $paymentCostInclVat;
    
	/**
     * @var int
     */
	protected $paymentCostExclVat;
    
	/**
     * @var int
     */
	protected $paymentCostVatDisplayPercentage;
    
    /**
     * @var string
     */
    protected $merchantOrderReference;
    
    /**
     * @var string
     */
    protected $description;
    
    /**
     * @var string
     */
    protected $customerIpAddress;
    
    /**
     * @var string
     */
    protected $configuredCultureOverride;
    
    /**
     * @var string
     */
    protected $returnUrlSuccessOverride;

    /**
     * @var string
     */
    protected $returnUrlCancelledOverride;
    
    /**
     * @var string
     */
    protected $returnUrlFailedOverride;
       
    /**
     * @var int[]|null
     */
    protected $hostedMethods;
    
    /**
     * @var bool
     */
    protected $enforceNoVAT;

    /**
     * @var IDeal
     */
    protected $iDeal;
    
    /**
     * @var Klarna
     */
    protected $klarna;
    
    /**
     * Create new payment request
     * 
     * @param int       $paymentMethod 
     * @param int       $currency 
     * @param int       $amount 
     * @param string    $merchantOrderReference 
     * @param string    $description
     * @param string    $customerIpAddress
     * @param string    $configuredCultureOverride
     * @param array|null $hostedMethods 
     * @param bool|null  $enforceNoVAT
     */
    public function __construct($paymentMethod = null, $currency = null, $amount = null, $merchantOrderReference = null, $description = null, $customerIpAddress = null, $configuredCultureOverride = null, array $hostedMethods = null, $enforceNoVAT = null )
    {
        $this->paymentMethod                = $paymentMethod;
        $this->currency                     = $currency;
        $this->amount                       = $amount;
        $this->merchantOrderReference       = $merchantOrderReference;
        $this->description                  = $description;
        $this->customerIpAddress            = $customerIpAddress;
        $this->configuredCultureOverride    = $configuredCultureOverride;
        $this->hostedMethods                = $hostedMethods;
        $this->enforceNoVAT                 = $enforceNoVAT;
    }
        
    /**
     * @return int
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
    
    /**
     * @param int $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }
    
    /**
     * @return int
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    
    /**
     * @param int $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
    	
	/**
     * @return int
     */
    public function getPaymentCostInclVat()
    {
        return $this->paymentCostInclVat;
    }
    
    /**
     * @param int $paymentCostInclVat
     */
    public function setPaymentCostInclVat($paymentCostInclVat)
    {
        $this->paymentCostInclVat = $paymentCostInclVat;
    }

	/**
     * @return int
     */
    public function getPaymentCostExclVat()
    {
        return $this->paymentCostExclVat;
    }
    
    /**
     * @param int $paymentCostExclVat
     */
    public function setPaymentCostExclVat($paymentCostExclVat)
    {
        $this->paymentCostExclVat = $paymentCostExclVat;
    }
    
	/**
     * @return int
     */
    public function getPaymentCostVatDisplayPercentage()
    {
        return $this->paymentCostVatDisplayPercentage;
    }
    
    /**
     * @param int $paymentCostVatDisplayPercentage
     */
    public function setPaymentCostVatDisplayPercentage($paymentCostVatDisplayPercentage)
    {
        $this->paymentCostVatDisplayPercentage = $paymentCostVatDisplayPercentage;
    }
    
    /**
     * @return string
     */
    public function getMerchantOrderReference()
    {
        return $this->merchantOrderReference;
    }
    
    /**
     * @param string $merchantOrderReference
     */
    public function setMerchantOrderReference($merchantOrderReference)
    {
        $this->merchantOrderReference = $merchantOrderReference;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * @return int[]|null
     */
    public function getHostedMethods()
    {
        return $this->hostedMethods;
    }
    
    /**
     * @param int[]|null $hostedMethods
     */
    public function setHostedMethods(array $hostedMethods = null)
    {
        $this->hostedMethods = $hostedMethods;
    }

    /**
     * @return bool
     */
    public function getEnforceNoVAT()
    {
        return $this->enforceNoVAT;
    }

    /**
     * @param bool $enforceNoVAT 
     */
    public function setEnforceNoVAT($enforceNoVAT)
    {
        $this->enforceNoVAT = $enforceNoVAT;
    }
    
    /**
     * @return string
     */
    public function getCustomerIpAddress()
    {
        return $this->customerIpAddress;
    }
        
    /**
     * @return string
     */
    public function getConfiguredCultureOverride()
    {
        return $this->configuredCultureOverride;
    }
        
    /**
     * @param string $configuredCultureOverride
     */
    public function setConfiguredCultureOverride($configuredCultureOverride)
    {
        $this->configuredCultureOverride = $configuredCultureOverride;
    }

    /**
     * Summary of setReturnUrlsOverride
     * @param string $returnUrlSuccessOverride 
     * @param string $returnUrlCancelledOverride 
     * @param string $returnUrlFailedOverride 
     */
    public function setReturnUrlsOverride($returnUrlSuccessOverride, $returnUrlCancelledOverride, $returnUrlFailedOverride)
    {
        $this->returnUrlSuccessOverride     = $returnUrlSuccessOverride;
        $this->returnUrlCancelledOverride   = $returnUrlCancelledOverride;
        $this->returnUrlFailedOverride      = $returnUrlFailedOverride;
    }
        
    /**
     * @return IDeal
     */
    public function getIDeal()
    {
        return $this->iDeal;
    }
    
    /**
     * @param IDeal $iDeal
     */
    public function setIDeal(IDeal $iDeal)
    {
        $this->iDeal = $iDeal;
    }
    
    /**
     * @return Klarna
     */
    public function getKlarna()
    {
        return $this->klarna;
    }
    
    /**
     * @param Klarna $klarna
     */
    public function setKlarna(Klarna $klarna)
    {
        $this->klarna = $klarna;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        if (is_object($value))
        {
            switch($name)
            {
                case 'iDeal':
                    $this->iDeal = new IDeal();
                    $this->iDeal->jsonDeserialize($value);
                    break;
                case 'klarna':
                    $this->klarna = new Klarna();
                    $this->klarna->jsonDeserialize($value);
                    break;
            }
        }
        else
        {
            parent::setJsonData($name, $value);
        }
    }
}