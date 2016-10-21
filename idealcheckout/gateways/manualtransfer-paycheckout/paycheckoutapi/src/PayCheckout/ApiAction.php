<?php

namespace PayCheckout;
    
class ApiAction
{
    const PAYMENT                               = 0; 
    const STATUSREQUEST                         = 10;
    
    const NOTIFY                                = 1000;
    const MANUAL_NOTIFY                         = 1001;
    const GET_LAST_NOTIFICATION_CONTENT         = 1002;
    
    const CANCEL_ORDER                          = 2000;
    const UPDATE_ORDER                          = 2001;
    
    const REFUND                                = 3000;
    const INCLUDED_IN_SEPA_BATCH                = 3001;
    
    // Queries
	const GET_PAYMENT_INFO					    = 4000;
	const GET_TRANSACTION_INFO        		    = 4001;
    
    const GET_CURRENT_CONFIGURATION             = 4010;
    const GET_AVAILABLE_PAYMENT_METHODS         = 4020;
    const GET_MODULE_VERSION                    = 4030;
    
	// Payment Specific functions
    const KLARNA_UPDATE_P_CLASSES               = 10000;
    const KLARNA_ACCOUNT_GET_INSTALLMENTS_INFO  = 10001;
    const KLARNA_GET_ADDRESSES					= 10002;
	const KLARNA_HAS_ACCOUNT                	= 10003;
    
    const IDEAL_GET_DIRECTORY                   = 11000;
}