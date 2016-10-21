<?php
    
namespace PayCheckout\Json\Generic\Invoice;
    
use PayCheckout\Json\Generic\Order\Item\Item;
    
class RefundItem extends Item
{
    /**
     * @var int
     */
    protected $refundAmount;
    
    /**
     * 
     * returns RefundAmount
     * 
     * @return int
     */
    function getRefundAmount()
    {
        return $this->refundAmount;
    }  
}
