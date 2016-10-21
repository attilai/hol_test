<?php
    
namespace PayCheckout\Json\Generic\Invoice;
    
use DateTime;
use PayCheckout\Json\JsonBase;
use PayCheckout\Json\Generic\Order\Identity\Identity;
use PayCheckout\Json\Generic\Order\Identity\Address;
use PayCheckout\Json\Generic\Order\Item\Item;
    
class Invoice extends JsonBase
{
    /**
     * @var DateTime
     */
    protected $createTime;
    
    /**
     * @var string
     */
    protected $invoiceNumber;
    
    /**
     * @var bool
     */
    protected $paid;
    
    /**
     * @var bool
     */
    protected $completelyRefunded;
    
    /**
     * @var Identity
     */
    protected $billingIdentity;
    
    /**
     * @var Address
     */
    protected $shippingAddress;
    
    /**
     * @var Item[]
     */
    protected $items;
    
    /**
     * @var string
     */
    protected $customerOrderRef;
    
    /**
     * @var string
     */
    protected $customerNote;
    
    /**
     * @var Refund[]
     */
    protected $refunds;
    
    /**
     * @var Discount[]
     */
    protected $discounts;
    
    /**
     * @var TrackAndTraceInfo
     */
    protected $trackAndTraceInfo;
    
    /**
     * @var integer
     */
    protected $quantityMultiplyFactor;
        
    /**
     * @var bool
     */
    protected $processedOffline;

    /**
     * Create new invoice
     */
    public function __construct() 
    {
        $this->items		= array();
        $this->refunds		= array();
        $this->discounts	= array();
    }
    
    /**
     * @return DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
        
    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->paid;
    }
        
    /**
     * @return bool
     */
    public function isCompletelyRefunded()
    {
        return $this->completelyRefunded;
    }
        
    /**
     * @return Identity
     */
    public function getBillingIdentity()
    {
        return $this->billingIdentity;
    }
        
    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
        
    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
        
    /**
     * @return string
     */
    public function getCustomerOrderReference()
    {
        return $this->customerOrderRef;
    }  
    
    /**
     * @return string
     */
    public function getCustomerNote()
    {
        return $this->customerNote;
    }
        
    /**
     * @return Refund[]
     */
    public function getRefunds()
    {
        return $this->refunds;
    }
       
    /**
     * @return Discount[]
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }
    
    /**
     * @return TrackAndTraceInfo
     */
    public function getTrackAndTraceInfo()
    {
        return $this->trackAndTraceInfo;
    }
    
    /**
     * @return int
     */
    public function getQuantityMultiplyFactor()
    {
        return $this->quantityMultiplyFactor;
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
            case 'billingIdentity':
                $this->billingIdentity = new Identity();
                $this->billingIdentity->jsonDeserialize($value);
                return;
            case 'shippingAddress':
                $this->shippingAddress = new Address();
                $this->shippingAddress->jsonDeserialize($value);
                return;
            case 'trackAndTraceInfo':
                $this->trackAndTraceInfo = new TrackAndTraceInfo();
                $this->trackAndTraceInfo->jsonDeserialize($value);
                return;
            case 'items':
                // Items needs to an array (list of items)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new item and add to items
                            $item = new Item();
                            $item->jsonDeserialize($itemValues);
							
                            $this->items[] = $item;
                        }
                    }
                }
                return;
            case 'refunds':
                // Refund needs to an array (list of refunds)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new refund and add to refunds
                            $item = new Refund();
                            $item->jsonDeserialize($itemValues);
							
                            $this->refunds[] = $item;
                        }
                    }
                }
                return;
            case 'discounts':
                // Discounts needs to an array (list of discounts)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new discount and add to discounts
                            $item = new Discount();
                            $item->jsonDeserialize($itemValues);
							
                            $this->discounts[] = $item;
                        }
                    }
                }
                return;
        }
		
        parent::setJsonData($name, $value);
    }
}
