<?php

namespace PayCheckout\Json\Generic\PaymentSpecific;

use PayCheckout\Json\JsonBase;

class SEPAbanktransfer extends JsonBase
{
    /**
     * @var string
     */
    protected $bankReference;
    
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
     * @return string
     */
    public function getConsumerName()
    {
        return $this->consumerName;
    }
    
    /**
     * @param string $consumerName 
     */
    public function setConsumerName($consumerName)
    {
        $this->consumerName = $consumerName;
    }
    
    /**
     * @return string
     */
    public function getBankReference()
    {
        return $this->bankReference;
    }
    
    /**
     * @return string
     */
    public function getConsumerBIC()
    {
        return $this->consumerBIC;
    }
    
    /**
     * @param string $consumerBIC 
     */
    public function setConsumerBIC($consumerBIC)
    {
        $this->consumerBIC = $consumerBIC;
    }
    
    /**
     * @return string
     */
    public function getConsumerIBAN()
    {
        return $this->consumerIBAN;
    }
    
    /**
     * @param string $consumerIBAN 
     */
    public function setConsumerIBAN($consumerIBAN)
    {
        $this->consumerIBAN = $consumerIBAN;
    }
}
