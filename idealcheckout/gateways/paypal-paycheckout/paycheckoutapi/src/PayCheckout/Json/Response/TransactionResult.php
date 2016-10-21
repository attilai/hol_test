<?php

namespace PayCheckout\Json\Response;

use PayCheckout\Json\Generic\PaymentSpecific\Klarna;
use PayCheckout\Json\Generic\PaymentSpecific\PayPal;
use PayCheckout\Json\Generic\PaymentSpecific\SofortBanking;
use PayCheckout\Json\JsonBase;

class TransactionResult extends JsonBase
{
    /**
	 * @var int|string
     */
    protected $transactionReference;
    
    /**
     * @var int
     */
    protected $status;
    
    /**
     * @var int|null
     */
    protected $paymentMethodReporting;
    
    /**
     * @var Klarna
     */
    protected $klarna;
        
    /**
     * @var PayPal
     */
    protected $paypal;
    
    /**
     * @var SofortBanking
     */
    protected $sofortBanking;
    
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
    public function getStatus()
    {
        return $this->status;
    }
       
    /**
     * @return int
     */
    public function getPaymentMethodReporting()
    {
        return $this->paymentMethodReporting;
    }
    
    /**
     * @return Klarna
     */
    public function getKlarna()
    {
        return $this->klarna;
    }
    
    /**
     * @return PayPal
     */
    public function getPayPal()
    {
        return $this->paypal;
    }
    
    /**
     * @return SofortBanking
     */
    public function getSofortBanking()
    {
        return $this->sofortBanking;
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
                case 'klarna':
                    $this->klarna = new Klarna();
                    $this->klarna->jsonDeserialize($value);
                    break;
                case 'payPal':
                    $this->paypal = new PayPal();
                    $this->paypal->jsonDeserialize($value);
                    break;
                case 'sofortBanking':
                    $this->sofortBanking = new SofortBanking();
                    $this->sofortBanking->jsonDeserialize($value);
                    break;
            }
        }
        else
        {
            parent::setJsonData($name, $value);
        }
    }
}