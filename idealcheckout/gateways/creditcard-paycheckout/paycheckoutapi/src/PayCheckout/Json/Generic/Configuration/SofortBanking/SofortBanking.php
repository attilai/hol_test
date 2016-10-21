<?php

namespace PayCheckout\Json\Generic\Configuration\SofortBanking;

use PayCheckout\Json\JsonBase;

class SofortBanking extends JsonBase
{   
    /**
     * @var int
     */
    protected $transactionTimeoutSeconds;
    
    /**
     * @var string[]
     */
    protected $enabledCountryCodes;
    
    /**
     * @return int
     */
    public function getTransactionTimeoutSeconds()
    {
        return $this->transactionTimeoutSeconds;
    }  

    /**
     * @return string[]
     */
    public function getEnabledCountries()
    {
        return $this->enabledCountryCodes;
    }
}