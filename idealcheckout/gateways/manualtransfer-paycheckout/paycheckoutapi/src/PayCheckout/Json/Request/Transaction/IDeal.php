<?php

namespace PayCheckout\Json\Request\Transaction;

use PayCheckout\Json\JsonBase;

class IDeal extends JsonBase
{
    /**
     * @var string
     */
    protected $issuingBank;
    
    /**
     * Create new iDEAL request
     * 
     * @param string $issuingBank 
     */
    public function __construct($issuingBank = null)
    {
        $this->issuingBank = $issuingBank;
    }
    
    /**
     * @return string
     */
    public function getIssuingBank()
    {
        return $this->issuingBank;
    }

    /**
     * @param string $issuingBank
     */
    public function setIssuingBank($issuingBank)
    {
        $this->issuingBank = $issuingBank;
    }
}