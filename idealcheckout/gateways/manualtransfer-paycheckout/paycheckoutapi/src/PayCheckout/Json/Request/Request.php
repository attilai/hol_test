<?php

namespace PayCheckout\Json\Request;

use PayCheckout\Json\JsonBase;
use PayCheckout\Json\Request\Transaction\Transaction;
use PayCheckout\Json\Generic\Order\Order;

class Request extends JsonBase
{
    /**
     * @var Transaction|null
     */
    protected $transaction;
    
    /**
     * @var Order|null
     */
    protected $order;
    
    /**
	 * @var int|string
     */
    protected $paymentReference;
    
    /**
     * @var mixed
     */
    protected $parameters;
    
    /**
     * Create new request with the given parameters
     * 
     * @param Transaction|null $transaction
     * @param Order|null $order
	 * @param int|string $paymentReference
     * @param mixed $parameters 
     */
    public function __construct(Transaction $transaction = null, Order $order = null, $paymentReference = null, $parameters = null)
    {
        $this->transaction      = $transaction;
        $this->order            = $order;
        $this->paymentReference = $paymentReference;
        $this->parameters       = $parameters;
    }
    
    /**
     * @return Transaction|null
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param Transaction|null $transaction
     */
    public function setTransaction(Transaction $transaction = null)
    {
        $this->transaction = $transaction;
    }
    
    /**
     * @return Order|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     */
    public function setOrder(Order $order = null)
    {
        $this->order = $order;
    }
    
    /**
	 * @return int|string
     */
    public function getPaymentReference()
    {
        return $this->paymentReference;
    }

    /**
	 * @param int|string $paymentReference
     */
    public function setPaymentReference($paymentReference)
    {
        $this->paymentReference = $paymentReference;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
    
    /**
     * @param string $parameterName 
     * @param string $parameterValue 
     * @return bool
     */
    public function addParameter($parameterName,$parameterValue)
    {
        if ($this->parameters != null && isset($this->parameters->$parameterName))
        {
            return false;
        }

        $this->parameters->$parameterName = $parameterValue;
        return true;
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
                case 'transaction':
                    $this->transaction = new Transaction();
                    $this->transaction->jsonDeserialize($value);
                    break;
                case 'order':
                    $this->order = new Order();
                    $this->order->jsonDeserialize($value);
            }
        }
        else
        {
            parent::setJsonData($name, $value);
        }
    }
}