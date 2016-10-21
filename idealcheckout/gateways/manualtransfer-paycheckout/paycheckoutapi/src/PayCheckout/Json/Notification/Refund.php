<?php

namespace PayCheckout\Json\Notification;

use DateTime;
use PayCheckout\Json\JsonBase;

class Refund extends JsonBase
{
    /**
     * @var DateTime
     */
    protected $createTime;
       
    /**
     * @var int
     */
    protected $refundAmountInclVat;
    
    /**
     * @var int
     */
    protected $refundAmountExclVat;
    
    /**
     * @var string
     */
    protected $refundNote;
    
    /**
     * @var string
     */
    protected $refundReference;
    
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
    public function getRefundAmountInclVat()
    {
        return $this->refundAmountInclVat;
    }
    
    /**
     * @return int
     */
    public function getRefundAmountExclVat()
    {
        return $this->refundAmountExclVat;
    }
    
    /**
     * @return string
     */
    public function getRefundNote()
    {
        return $this->refundNote;
    }
    
    /**
     * @return string
     */
    public function getRefundReference()
    {
        return $this->refundReference;
    }
}
