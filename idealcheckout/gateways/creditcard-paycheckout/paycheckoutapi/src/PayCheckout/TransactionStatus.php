<?php

namespace PayCheckout;
    
class TransactionStatus
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
    const REVERSED              = 200;
    const API_SUCCESS			= 1000;
    const API_PARTIAL_OK    	= 1010;
    const API_FAILED		    = 1020;
}