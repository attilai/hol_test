<?php

namespace PayCheckout\Json\Notification;

use PayCheckout\Json\Generic\Order\Order;
use PayCheckout\Json\JsonBase;

class PaymentStatusChange extends JsonBase
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
    protected $paymentMethod;
    
    /**
     * @var int
     */
    protected $status;

    /**
     * @var int
     */
    protected $currency;
     
    /**
     * @var string
     */
    protected $merchantOrderReference;
    
    /**
     * @var int
     */
    protected $paymentAmount;
    
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
     * @var Order
     */
    protected $order;

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
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
    
    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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
     * @return int
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
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
    public function getPaymentCostExclVat()
    {
        return $this->paymentCostExclVat;
    }
    
    /**
     * @return int
     */
    public function getPaymentCostVatDisplayPercentage()
    {
        return $this->paymentCostVatDisplayPercentage;
    }
    
    /**
     * @return order
     */
    public function getOrder()
    {
        return $this->order;
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
                case 'order':
                    $this->order = new Order();
                    $this->order->jsonDeserialize($value);
                    break;
            }
        }
        else
        {
            parent::setJsonData($name, $value);
        }
    }    
}
