<?php

namespace PayCheckout\Api\Service\Klarna;

class InstallmentInfo
{
    /**
     * @var string
     */
    private $campaignName;
    
    /**
     * @var int
     */
    private $totalInstallments;
    
    /**
     * @var int
     */
    private $monthlyAmount;
    
    /**
     * @var int
     */
    private $pClassId;
    
    /**
     * @var int
     */
    private $currency;
    
    /**
     * Create new installment info
     * 
     * @param string $campaignName 
     * @param int $totalInstallments 
     * @param int $monthlyAmount 
     * @param int $pClassId 
     * @param int $currency
     */
    public function __construct($campaignName, $totalInstallments, $monthlyAmount, $pClassId, $currency)
    {
        $this->campaignName         = $campaignName;
        $this->totalInstallments    = $totalInstallments;
        $this->monthlyAmount        = $monthlyAmount;
        $this->pClassId             = $pClassId;
        $this->currency             = $currency;
    }
        
    /**
     * @return string
     */
    public function getCampaignName()
    {
        return $this->campaignName;
    }
    
    /**
     * @return int
     */
    public function getTotalInstallments()
    {
        return $this->totalInstallments;
    }
    
    /**
     * @return int
     */
    public function getMonthlyAmount()
    {
        return $this->monthlyAmount;
    }
    
    /**
     * @return int
     */
    public function getPClassId()
    {
        return $this->pClassId;
    }
    
    /**
     * @return int
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}