<?php

namespace PayCheckout\Json\Generic\Configuration\MinMaxSelector;

use PayCheckout\Json\JsonBase;

class MinMaxSelector extends JsonBase
{
    /**
     * @var int
     */
    protected $minimumCharge;
    
    /**
     * @var int
     */
    protected $maximumCharge;
    
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
    public function getMaximumCharge()
    {
        return $this->maximumCharge;
    }
}