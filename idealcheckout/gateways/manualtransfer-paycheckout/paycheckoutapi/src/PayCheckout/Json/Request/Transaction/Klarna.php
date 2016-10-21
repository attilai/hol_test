<?php

namespace PayCheckout\Json\Request\Transaction;

use PayCheckout\Json\JsonBase;

class Klarna extends JsonBase
{
    /**
     * @var int
     */
    protected $pClassId;
    
    /**
     * Create new Klarna request
     * 
     * @param int $pClassId 
     */
    public function __construct($pClassId = null)
    {
        $this->pClassId = $pClassId;
    }
    
    /**
     * @return int
     */
    public function getPClassId()
    {
        return $this->pClassId;
    }
    
    /**
     * @param int $pClassId
     */
    public function setPClassId($pClassId)
    {
        $this->pClassId = $pClassId;
    }
}