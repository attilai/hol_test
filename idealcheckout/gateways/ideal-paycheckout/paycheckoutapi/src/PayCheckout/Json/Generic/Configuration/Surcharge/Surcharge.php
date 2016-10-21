<?php

namespace PayCheckout\Json\Generic\Configuration\Surcharge;

use PayCheckout\Json\JsonBase;

class Surcharge extends JsonBase
{
    /**
     * @var int
     */
    protected $minimumCharge;
    
    /**
     * @var int
     */
    protected $fixedCharge;
    
    /**
     * @var int
     */
    protected $percentage;
    
    /**
     * @var int
     */
    protected $roundOfValue;
    
    /**
     * @return int
     */
    public function getMinimumCharge()
    {
        return $this->minimumCharge;
    }
    
    /**
     * @return int
     */
    public function getFixedCharge()
    {
        return $this->fixedCharge;
    }
    
    /**
     * @return int
     */
    public function getPercentage()
    {
        return $this->percentage;
    }
    
    /**
     * @return int
     */
    public function getRoundOfValue()
    {
        return $this->roundOfValue;
    }
}