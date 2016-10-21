<?php

namespace PayCheckout\Json\Generic\Invoice;
use PayCheckout\Json\JsonBase;

use DateTime;

class Discount extends JsonBase
{
    /**
     * @var DateTime
     */
    protected $createTime;
    
    /**
     * @var string
     */
    protected $discountReference;
    
    /**
     * @var int
     */
    protected $amountInclusiveVat;
    
    /**
     * @var int
     */
    protected $amountExclusiveVat;
    
    /**
     * @var int
     */
    protected $vatDisplayPercentage;
    
    /**
     * @var string
     */
    protected $customerNote;

    /**
     * @var bool
     */
    protected $processedOffline;
    
    /**
     * @return DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
    
    /**
     * @param DateTime $createTime 
     */
    public function setCreateTime(DateTime $createTime)
    {
        $this->createTime = $createTime;
    }
    
    /**
     * @return string
     */
    public function getDiscountReference()
    {
        return $this->discountReference;
    }
    
    /**
     * @param string $discountReference
     */
    public function setDiscountReference($discountReference)
    {
        $this->discountReference = $discountReference;
    }
    
    /**
     * @return int
     */
    public function getAmountInclusiveVat()
    {
        return $this->amountInclusiveVat;
    }
    
    /**
     * @param int $amountInclusiveVat
     */
    public function setAmountInclusiveVat($amountInclusiveVat)
    {
        $this->amountInclusiveVat = $amountInclusiveVat;
    }
    
    /**
     * @return int
     */
    public function getAmountExclusiveVat()
    {
        return $this->amountExclusiveVat;
    }
    
    /**
     * @param int $amountExclusiveVat
     */
    public function setAmountExclusiveVat($amountExclusiveVat)
    {
        $this->amountExclusiveVat = $amountExclusiveVat;
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
    
    /**
     * @return string
     */
    public function getCustomerNote()
    {
        return $this->customerNote;
    }
    
    /**
     * @param string $customerNote
     */
    public function setCustomerNote($customerNote)
    {
        $this->customerNote = $customerNote;
    }

    /**
     * @return bool
     */
    public function getProcessedOffline()
    {
        return $this->processedOffline;
    }
   
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        switch($name)
        {
            case 'createTime':
                $this->createTime = new DateTime($value);
                return;
        }
		
        parent::setJsonData($name, $value);
    }
}
