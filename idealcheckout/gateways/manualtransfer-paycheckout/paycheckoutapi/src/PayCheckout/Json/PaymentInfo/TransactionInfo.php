<?php

namespace PayCheckout\Json\PaymentInfo;

use DateTime;
use PayCheckout\Json\Generic\Invoice\Discount;
use PayCheckout\Json\Generic\Invoice\Invoice;
use PayCheckout\Json\Generic\Order\Order;
use PayCheckout\Json\JsonBase;
use PayCheckout\Json\Request\Request;
use PayCheckout\Json\Response\Response;
use PayCheckout\Json\Generic\PaymentSpecific\IDeal;
use PayCheckout\Json\Generic\PaymentSpecific\PayPal;
use PayCheckout\Json\Generic\PaymentSpecific\SofortBanking;
use PayCheckout\Json\Generic\PaymentSpecific\SEPAbanktransfer;

class TransactionInfo extends JsonBase
{
    /**
	 * @var int|string
	 */
    protected $transactionReference;
    
    /**
	 * @var int|string
	 */
    protected $traceReference;
    
    /**
	 * @var DateTime
	 */
    protected $lastUpdateTime;
    
    /**
	 * @var int
	 */
    protected $action;
	
    /**
	 * @var int
	 */
    protected $reservationMutationAmount;
    
    /**
	 * @var int
	 */
    protected $balanceMutationAmount;
	
	/**
	 * @var int
	 */
    protected $status;
    
    /**
     * @var int
     */
    protected $paymentStatus;
    
	/**
     * @var string
     */
    protected $subStatus;
    
	/**
	 * @var int
	 */
	protected $accountAction;
    
	/**
     * @var int
     */
	protected $paymentMethod;
    
    /**
     * @var string
     */
    protected $paymentMethodDescription;
    
    /**
	 * @var int|string
     */
    protected $relatedPaymentReference;
        
    /**
     * @var int
     */
    protected $relatedTransactionIndex;
        
    /**
     * @var DateTime
     */
    protected $hostedExpirationTime;
    
    /**
	 * @var string
	 */
    protected $ipAddress;
    
    /**
	 * @var Request
	 */
    protected $request;
    
    /**
	 * @var Response
	 */
    protected $response;
        
    /**
	 * @var Order
	 */
    protected $previousOrder;
    
    /**
	 * @var Order
	 */
    protected $currentOrder;
    
    /**
	 * @var Invoice[]
	 */
    protected $invoices;
    
    /**
	 * @var Discount[]
	 */
    protected $customerDiscounts;
    
    /**
     * @var iDeal
     */
    protected $iDeal;

    /**
     * @var PayPal
     */
    protected $payPal;

    /**
     * @var SofortBanking
     */
    protected $sofortBanking;

    /**
     * @var SEPAbanktransfer
     */
    protected $sepaBanktransfer;

    /**
     * @var bool
     */
    protected $processedOffline;
    
    /** 
	 * Create new transaction info
	 */
    public function __construct()
    {
        $this->invoices             = array();
        $this->customerDiscounts    = array();
    }
        
    /**
	 * @return int|string
	 */
    public function getTransactionReference()
    {
        return $this->transactionReference;
    }
         
    /**
	 * @return int|string
	 */
    public function getTraceReference()
    {
        return $this->traceReference;
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
    public function getAction()
    {
        return $this->action;
    }
    
	/**
	 * @return int
	 */
    public function getReservationMutationAmount()
    {
        return $this->reservationMutationAmount;
    }
    
	/**
	 * @return int
	 */
    public function getBalanceMutationAmount()
    {
        return $this->balanceMutationAmount;
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
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }
    
    /**
     * @return string
     */
    public function getSubStatus()
    {
        return $this->subStatus;
    }
    
	/**
	 * @return int
	 */
    public function getAccountAction()
    {
        return $this->accountAction;
    }
    
	/**
     * @return int
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
    
	/**
     * @return string
     */
    public function getPaymentMethodDescription()
    {
        return $this->paymentMethodDescription;
    }
    
    /**
	 * @return int|string
     */
    public function getRelatedPaymentReference()
    {
        return $this->relatedPaymentReference;
    }
    
    /**
     * @return int
     */
    public function getRelatedTransactionIndex()
    {
        return $this->relatedTransactionIndex;
    }
        
    /**
     * @return DateTime
     */
    public function getHostedExpirationTime()
    {
        return $this->hostedExpirationTime;
    }
        
    /**
	 * @return string
	 */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }
        
    /**
	 * @return Request
	 */
    public function getRequest()
    {
        return $this->request;
    }
    
	/**
	 * @return Response
	 */
    public function getResponse()
    {
        return $this->response;
    }
            
	/**
	 * @return Order
	 */
    public function getPreviousOrder()
    {
        return $this->previousOrder;
    }
    
	/**
	 * @return Order
	 */
    public function getCurrentOrder()
    {
        return $this->currentOrder;
    }
        
    /**
	 * @return Invoice[]
	 */
    public function getInvoices()
    {
        return $this->invoices;
    }
        
    /**
	 * @return Discount[]
	 */
    public function getCustomerDiscounts()
    {
        return $this->customerDiscounts;
    }
        
    /**
     * @return bool
     */
    public function getProcessedOffline()
    {
        return $this->processedOffline;
    }
    
    /**
     * @return IDeal
     */
    public function getIDeal()
    {
        return $this->iDeal;
    }
    
    /**
     * @return PayPal
     */
    public function getPayPal()
    {
        return $this->payPal;
    }
    
    /**
     * @return SofortBanking
     */
    public function getSofortBanking()
    {
        return $this->sofortBanking;
    }  

    /**
     * @return SEPAbanktransfer
     */
    public function getSEPAbanktransfer()
    {
        return $this->sepaBanktransfer;
    }  

    /**
	 * {@inheritDoc}
	 */
    protected function setJsonData($name, $value)
    {
        switch($name)
        {
            case 'lastUpdateTime':
                $this->lastUpdateTime = new DateTime($value);
                return;
            case 'hostedExpirationTime':
                $this->hostedExpirationTime = new DateTime($value);
                return;
            case 'request':
                $this->request = new Request();
                $this->request->jsonDeserialize($value);
                return;
            case 'response':
                $this->response = new Response();
                $this->response->jsonDeserialize($value);
                return;
            case 'previousOrder':
                $this->previousOrder = new Order();
                $this->previousOrder->jsonDeserialize($value);
                return;
            case 'currentOrder':
                $this->currentOrder = new Order();
                $this->currentOrder->jsonDeserialize($value);
                return;
            case 'invoices':
                // Invoices needs to an array (list of invoices)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new item and add to items
                            $item = new Invoice();
                            $item->jsonDeserialize($itemValues);
                            $this->invoices[] = $item;
                        }
                    }
                }
                return;
            case 'customerDiscounts':
                // Customer discounts needs to an array (list of discounts)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new item and add to items
                            $item = new Discount();
                            $item->jsonDeserialize($itemValues);							
                            $this->customerDiscounts[] = $item;
                        }
                    }
                }
                return;
            case 'iDeal':
                $this->iDeal = new IDeal();
                $this->iDeal->jsonDeserialize($value);
                return;
            case 'payPal':
                $this->payPal = new PayPal();
                $this->payPal->jsonDeserialize($value);
                return;
            case 'sofortBanking':
                $this->sofortBanking = new SofortBanking();
                $this->sofortBanking->jsonDeserialize($value);
                return;
            case 'sEPAbanktransfer':
                $this->sepaBanktransfer = new SEPAbanktransfer();
                $this->sepaBanktransfer->jsonDeserialize($value);
                return;
        }
		
        parent::setJsonData($name, $value);
    }
}