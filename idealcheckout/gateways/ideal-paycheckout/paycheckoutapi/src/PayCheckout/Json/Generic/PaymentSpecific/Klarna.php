<?php

namespace PayCheckout\Json\Generic\PaymentSpecific;

use PayCheckout\Json\JsonBase;

class Klarna extends JsonBase
{
    /**
     * @var string
     */
    protected $reservationNumber;
    
    /**
     * @var string
     */
    protected $klarnaOrderStatus;
    
    /**
     * @var int
     */
    protected $pClassIdUsed;
    
    /**
     * @return string
     */
    public function getReservationNumber()
    {
        return $this->reservationNumber;
    }
    
    /**
     * @param string $reservationNumber 
     */
    public function setReservationNumber($reservationNumber)
    {
        $this->reservationNumber = $reservationNumber;
    }
    
    /**
     * @return string
     */
    public function getKlarnaOrderStatus()
    {
        return $this->klarnaOrderStatus;
    }
    
    /**
     * @param string $klarnaOrderStatus 
     */
    public function setKlarnaOrderStatus($klarnaOrderStatus)
    {
        $this->klarnaOrderStatus = $klarnaOrderStatus;
    }
    
    /**
     * @return string
     */
    public function getPClassIdUsed()
    {
        return $this->pClassIdUsed;
    }
    
    /**
     * @param string $pClassIdUsed 
     */
    public function setPClassIdUsed($pClassIdUsed)
    {
        $this->pClassIdUsed = $pClassIdUsed;
    }
}
