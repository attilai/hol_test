<?php

namespace PayCheckout\Json\Generic\Configuration\IDeal;

use PayCheckout\Json\JsonBase;

class IDeal extends JsonBase
{
    /**
     * @var int
     */
    protected $expirationPeriodInMinutes;   
    
    /**
     * @return int
     */
    public function getExpirationPeriodInMinutes()
    {
        return $this->expirationPeriodInMinutes;
    }   
 }
