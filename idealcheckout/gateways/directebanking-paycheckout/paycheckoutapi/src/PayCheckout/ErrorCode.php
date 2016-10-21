<?php

namespace PayCheckout;
    
class ErrorCode
{
    // Connection problems with PayCheckout system client side
    const CAN_NOT_CONNECT_WITH_PAY_CHECKOUT			    	    = 1000;
    const PAY_CHECKOUT_API_CALL_TIMED_OUT					    = 1001;
    const PAY_CHECKOUT_API_CALL_INVALID_RESPONSE			    = 1002;
    const PAY_CHECKOUT_API_CALL_RESPONSE_COMPROMISED		    = 1003;
    
    // Server generated error on content supplied
    const REQUEST_CONTENT_SECURITY_VALIDATION_FAILED		    = 2001;
    const REQUEST_CONTENT_VALIDATION_FAILED					    = 2002;
    const REQUEST_ERROR_PROCESSING_RESULT_FROM_REMOTE_PARTY	    = 2003;
    
    // GetAvailablePaymentMethod specific
	const REQUEST_NO_SUITABLE_PAYMENTMETHOD_FOUND			    = 2500;
    
    // Connected party issues
    const NOT_ABLE_TO_CONNECT_TO_REMOTE_PARTY				    = 3000;
    const REMOTE_PARTY_RESPONSE_TIME_OUT		    		    = 3001;
    const REMOTE_PARTY_REPORTS_VALIDATION_FAILED			    = 3002;
    const REMOTE_PARTY_REPORTS_SECURITY_VALIDATION_FAILED       = 3003;
    const REMOTE_PARTY_REPORTS_MAINTENANCE					    = 3004;
    const REMOTE_PARTY_REPORTS_BUSY_TRY_AGAIN_LATER			    = 3005;
    const REMOTE_PARTY_REPORTS_INTERNAL_ERROR				    = 3006;
    const REMOTE_PARTY_REPORTS_CUSTOMER_NOT_ACCEPTED		    = 3007;
	const REMOTE_PARTY_REPORTS_INSUFFICIENTFUNDS                = 3008;
	const REMOTE_PARTY_REPORTS_COMMUNICATION_PROBLEM_THIRDPARTY = 3009;
    const REMOTE_PARTY_REPORTS_UNSPECIFIED                      = 3010;
	
    
    // Payment specific issues
    const PAYMENT_STATUS_NOT_VALID_ALREADY_PAID                 = 4000;  
    const PAYMENT_STATUS_NOT_VALID_EXPIRED                      = 4001;
    const PAYMENT_STATUS_NOT_VALID_CANCELLED                    = 4002;
    const PAYMENT_STATUS_NOT_VALID_REJECTED                     = 4003;
    const PAYMENT_DATA_IN_COMPLETE_OR_UNKNOWN                   = 4010;
    const PAYMENT_TYPE_NOT_ALLOWED_TO_RUN_LIVE                  = 4011;
    const PAYMENT_TYPE_NOT_CONFIGURED                           = 4012;
    const PAYMENT_TYPE_CONFIGURED_NOT_ALLOWED                   = 4013;
}