<?php

namespace PayCheckout\Json\PaymentInfo;

use DateTime;
use PayCheckout\Json\JsonBase;

class PaymentInfo extends JsonBase
{
    /**
	 * @var DateTime
	 */
    protected $createTime;
	
	/**
	 * @var int
	 */
	protected $currency;
	
	/**
	 * @var int
	 */
	protected $paymentAmount;
    
	/**
	 * @var int
	 */
	protected $reservedAmount;
	
	/**
	 * @var int
	 */
	protected $balance;
    
    /**
	 * @var DateTime
	 */
    protected $lastUpdateTime;
    
    /**
	 * @var int
	 */
    protected $status;
    
    /**
	 * @var TransactionInfo[]
	 */
    protected $transactions;
    
    /**
	 * Create new payment info
	 */
    public function __construct()
    {
        $this->transactions = array();
    }
    
    /**
	 * @return DateTime
	 */
    public function getCreateTime()
    {
        return $this->createTime;
    }
    	
	/**
	 * @return int 
	 */
    public function getCurrency()
    {
        return $this->currency;
    }
        
	/**
	 * @return int 
	 */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }
        
	/**
	 * @return int 
	 */
    public function getReservedAmount()
    {
        return $this->reservedAmount;
    }
    	
	/**
	 * @return int 
	 */
    public function getBalance()
    {
        return $this->balance;
    }
        
    /**
	 * @return DateTime
	 */
    public function getLastUpdateTime()
    {
        return $this->lastUpdateTime;
    }
        
    /**
	 * @return int
	 */
    public function getPaymentStatus()
    {
        return $this->status;
    }
        
    /**
	 * @return TransactionInfo[]
	 */
    public function getTransactions()
    {
        return $this->transactions;
    }
        
    /**
     * Add transaction
     * 
     * @param TransactionInfo $transactionInfo
     */
    private function addTransaction(TransactionInfo $transactionInfo)
    {
        $this->transactions[] = $transactionInfo;
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
            case 'lastUpdateTime':
                $this->lastUpdateTime = new DateTime($value);
                return;
            case 'transactions':
                // Transactions needs to an array (list of transactions)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new item and add to items
                            $item = new TransactionInfo();
                            $item->jsonDeserialize($itemValues);
							
                            $this->addTransaction($item);
                        }
                    }
                }
                return;
        }
		
        parent::setJsonData($name, $value);
    }
}