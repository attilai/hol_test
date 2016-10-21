<?php

namespace PayCheckout\Json\Parameter;

use PayCheckout\Json\JsonBase;

class RefundCost extends JsonBase
{
    /**
     * @var string
     */
    protected $costName;
    
    /**
     * @var string
     */
    protected $costDescription;
    
    /**
     * @var int
     */
    protected $costExclusiveVat;
    
    /**
     * @var int
     */
    protected $costInclusiveVat;
    
    /**
     * @var int
     */
    protected $vatDisplayPercentage;
    
    /**
     * @return string
     */
    public function getCostName()
    {
        return $this->costName;
    }
    
    /**
     * @param string $costName 
     */
    public function setCostName($costName)
    {
        $this->costName = $costName;
    }
    
    /**
     * @return string
     */
    public function getCostDescription()
    {
        return $this->costDescription;
    }
    
    /**
     * @param string $costDescription
     */
    public function setCostDescription($costDescription)
    {
        $this->costDescription = $costDescription;
    }
    
    /**
     * @return int
     */
    public function getCostExclusiveVat()
    {
        return $this->costDescription;
    }
    
    /**
     * @param int $costExclusiveVat
     */
    public function setCostExclusiveVat($costExclusiveVat)
    {
        $this->costExclusiveVat = $costExclusiveVat;
    }
    
    /**
     * @return int
     */
    public function getCostInclusiveVat()
    {
        return $this->costDescription;
    }
    
    /**
     * @param int $costInclusiveVat
     */
    public function setCostInclusiveVat($costInclusiveVat)
    {
        $this->costInclusiveVat = $costInclusiveVat;
    }
    
    /**
     * @return int
     */
    public function getVatDisplayPercentage()
    {
        return $this->vatDisplayPercentage;
    }
    
    /**
     * @param int $vatDisplayPercentage
     */
    public function setVatDisplayPercentage($vatDisplayPercentage)
    {
        $this->vatDisplayPercentage = $vatDisplayPercentage;
    }
}
