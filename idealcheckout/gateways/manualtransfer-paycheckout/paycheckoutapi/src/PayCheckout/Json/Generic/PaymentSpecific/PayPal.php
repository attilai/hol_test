<?php

namespace PayCheckout\Json\Generic\PaymentSpecific;

use PayCheckout\Json\JsonBase;

class PayPal extends JsonBase
{
    /**
     * @var string
     */
    protected $paymentTransactionId;
    
    /**
     * @return string
     */
    public function getPaymentTransactionId()
    {
        return $this->paymentTransactionId;
    }
}
