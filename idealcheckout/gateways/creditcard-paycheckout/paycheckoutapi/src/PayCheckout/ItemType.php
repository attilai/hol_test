<?php

namespace PayCheckout;
    
class ItemType
{
    const ARTICLE           = 0; 
    const SHIPPING_COST     = 1;
    const PAYMENT_COST      = 2;
    const DISCOUNT          = 3;
    const ADDITIONAL_COST   = 4;
    const REFUND_COST       = 5;
}