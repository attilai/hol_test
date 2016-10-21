<?php

namespace PayCheckout\Json\Generic\Configuration\PayPal;

use PayCheckout\Json\JsonBase;

class PayPal extends JsonBase
{
    /**
     * Summary of $payPalAccountToUse
     * @var string
     */
    protected $payPalAccountToUse;
     
    /**
     * @return string
     */
    public function getPayPalAccountToUse()
    {
        return $this->payPalAccountToUse;
    }
}
