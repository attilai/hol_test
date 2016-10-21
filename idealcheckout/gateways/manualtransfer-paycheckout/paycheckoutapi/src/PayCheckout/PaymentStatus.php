<?php

namespace PayCheckout;
    
class PaymentStatus
{
    const PENDING			    = 10;
    const APPROVAL_PENDING      = 20;
    const PAID			        = 30;
    const CANCELLED		        = 40;
    const FAILED			    = 50;
    const REJECTED		        = 60;
    const EXPIRED			    = 70;
    const REFUNDED              = 80;
    const RESERVED              = 90;
    const HOSTED_INITIATED      = 100;
    const HOSTED_EXPIRED        = 110;
    const REVERSED              = 200;
}