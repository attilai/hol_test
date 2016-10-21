<?php
    
namespace PayCheckout\Json\Generic\Invoice;
    
use DateTime;
use PayCheckout\Json\JsonBase;
use PayCheckout\Json\Parameter\RefundCost;
    
class Refund extends JsonBase
{
    /**
     * @var DateTime
     */
    protected $createTime;
    
    /**
     * @var string
     */
    protected $refundReference;
    
    /**
     * @var RefundItem[]
     */
    protected $items;
    
    /**
     * @var RefundCost
     */
    protected $refundCost;

    /**
     * @var bool
     */
    protected $processedOffline;
    
    /**
     * Create new refund
     */
    public function __construct()
    {
        $this->items = array();
    }
    
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
    public function getRefundReference()
    {
        return $this->refundReference;
    }
    
    /**
     * @param string $refundReference
     */
    public function setRefundReference($refundReference)
    {
        $this->refundReference = $refundReference;
    }
    
    /**
     * @return RefundItem[]
     */
    public function getItems()
    {
        return $this->items;
    }
        
    /**
     * @return RefundCost
     */
    public function getRefundCost()
    {
        return $this->refundCost;
    }
    
    /**
     * @param RefundCost $refundCost 
     */
    public function setRefundCost(RefundCost $refundCost)
    {
        $this->refundCost = $refundCost;    
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
                            $item = new RefundItem();
                            $item->jsonDeserialize($itemValues);
							
                            $this->items[] =$item;
                        }
                    }
                }
                return;
            case 'refundCost':
                $this->refundCost = new RefundCost();
                $this->refundCost->jsonDeserialize($value);
                return;
        }
		
        parent::setJsonData($name, $value);
    }
}
