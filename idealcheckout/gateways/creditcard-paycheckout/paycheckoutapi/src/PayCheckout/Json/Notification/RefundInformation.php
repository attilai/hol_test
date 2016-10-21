<?php

namespace PayCheckout\Json\Notification;

use PayCheckout\Json\JsonBase;

class RefundInformation extends JsonBase
{
    /**
	 * @var int|string
     */
    protected $paymentReference;
       
    /**
	 * @var int|string
     */
    protected $transactionReference;
    
    /**
     * @var int
     */
    protected $currency;
        
    /**
     * @var string
     */
    protected $merchantOrderReference;
    
    /**
     * @var Refund[]
     */
    protected $refunds;
    
    /** 
     * Create new RefundInformation
     */
    public function __construct()
    {
        $this->refunds = array();
    }
    
    /**
	 * @return int|string
     */
    public function getPaymentReference()
    {
        return $this->paymentReference;
    }
    
    /**
	 * @return int|string
     */
    public function getTransactionReference()
    {
        return $this->transactionReference;
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
    public function getMerchantOrderReference()
    {
        return $this->merchantOrderReference;
    }
        
    /**
     * @return Refund[]
     */
    public function getRefunds()
    {
        return $this->refunds;
    }
   
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        switch($name)
        {
            case 'refunds':
                // Refunds needs to translated into an array (list of refunds)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new item and add to items
                            $item = new Refund();
                            $item->jsonDeserialize($itemValues);
							
                            $this->refunds[] = $item;
                        }
                    }
                }
                return;
        }
		
        parent::setJsonData($name, $value);
    }
    
}
