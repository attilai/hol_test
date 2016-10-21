<?php

namespace PayCheckout\Json\Generic\PaymentSpecific;

use PayCheckout\Json\JsonBase;

class SofortBanking extends JsonBase
{
    /**
     * @var bool
     */
    protected $testPayment; 
    
    /**
     * @var string
     */
	protected $sofortTransaction;
    
	/**
     * @var string
     */
	protected $consumerName;
    
	/**
     * @var string
     */
	protected $consumerBIC;
    
	/**
     * @var string
     */
	protected $consumerIBAN;
    
	/**
     * @var string
     */
	protected $bankCode;
    
	/**
     * @var string
     */
	protected $bankName;
    
	/**
     * @var string
     */
	protected $accountNumber;
    
	/**
     * @var string
     */
	protected $countryCode;
    
	/**
	 * @return bool
	 */
	public function getTestPayment()
    {
        return $this->testPayment;
    }
    
    /**
     * @return string
     */
    public function getSofortTransaction()
    {
        return $this->sofortTransaction;
    }
    
    /**
     * @return string
     */
	public function getConsumerName()
    {
        return $this->consumerName;
    }
    
    /**
     * @return string
     */
	public function getConsumerBIC()
    {
        return $this->consumerBIC;
    }
    
    /**
     * @return string
     */
	public function getconsumerIBAN()
    {
        return $this->consumerIBAN;
    }
    
    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }
    
    /**
     * @return string
     */
	public function getBankName()
    {
        return $this->bankName;
    }
    
    /**
     * @return string
     */
	public function getAccountNumber()
    {
        return $this->accountNumber;
    }
    
    /**
     * @return string
     */
	public function getCountryCode()
    {
        return $this->countryCode;
    }   
}